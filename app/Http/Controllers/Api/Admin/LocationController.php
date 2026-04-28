<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Location\StoreLocationRequest;
use App\Http\Requests\Location\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return LocationResource::collection(location::latest()->take(12)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocationRequest $request): JsonResponse
    {
        $location = location::create($request->validated());

        return response()->json([
            'message' => 'Location created successfully',
            'data' => new LocationResource($location)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(location $location): LocationResource
    {
        return new LocationResource($location);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLocationRequest $request, location $location): JsonResponse
    {
        $location->update($request->validated());

        return response()->json([
            'message' => 'Location updated successfully',
            'data' => new LocationResource($location)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(location $location): JsonResponse
    {
        $location->delete();

        return response()->json([
            'message' => 'Location deleted successfully'
        ]);
    }
}
