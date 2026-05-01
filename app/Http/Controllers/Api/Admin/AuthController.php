<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Resources\Admin\AdminResource;
use Illuminate\Http\JsonResponse;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Get a token via given credentials.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        Log::info('Login attempt', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            $admin = Admin::where('email', $credentials['email'])->first();

            if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
                Log::warning('Login failed: Unauthorized credentials', [
                    'email' => $credentials['email'],
                    'ip' => $request->ip()
                ]);
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $token = $admin->createToken('admin-token')->plainTextToken;

            Log::info('Login successful', [
                'email' => $credentials['email'],
                'ip' => $request->ip()
            ]);

            return $this->respondWithToken($token, $admin);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage(), [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return AdminResource
     */
    public function me(): AdminResource
    {
        return new AdminResource(Auth::guard('admin')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::guard('admin')->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     * @param Admin $admin
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token, Admin $admin): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => new AdminResource($admin),
        ]);
    }
}
