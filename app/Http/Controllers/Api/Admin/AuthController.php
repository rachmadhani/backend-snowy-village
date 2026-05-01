<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use App\Http\Resources\Admin\AdminResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
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
            if (! $token = Auth::guard('admin')->attempt($credentials)) {
                Log::warning('Login failed: Unauthorized credentials', [
                    'email' => $credentials['email'],
                    'ip' => $request->ip()
                ]);
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            Log::info('Login successful', [
                'email' => $credentials['email'],
                'ip' => $request->ip()
            ]);

            return $this->respondWithToken($token);
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
        Auth::guard('admin')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(Auth::guard('admin')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('admin')->factory()->getTTL() * 60,
            'user' => new AdminResource(Auth::guard('admin')->user()),
        ]);
    }
}
