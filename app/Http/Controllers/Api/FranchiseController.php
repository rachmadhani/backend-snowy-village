<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFranchiseRequest;
use App\Http\Resources\FranchiseResource;
use App\Mail\FranchiseInquiryMail;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Mail;

class FranchiseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return FranchiseResource::collection(Franchise::latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFranchiseRequest $request): FranchiseResource
    {
        $franchise = Franchise::create($request->validated());

        $emailSent = false;

        try {
            Mail::to('denny@snowyvillages.com')->send(new FranchiseInquiryMail($franchise));
            $emailSent = true;
        } catch (\Exception $e) {
            report($e);
        }

        return (new FranchiseResource($franchise))->additional([
            'email_sent' => $emailSent,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Franchise $franchise): FranchiseResource
    {
        return new FranchiseResource($franchise);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFranchiseRequest $request, Franchise $franchise): FranchiseResource
    {
        $franchise->update($request->validated());

        return new FranchiseResource($franchise);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Franchise $franchise)
    {
        $franchise->delete();

        return response()->json(['message' => 'Franchise inquiry deleted successfully']);
    }
}
