<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LineOfBusinessRequest;
use App\Http\Resources\LineOfBusinessResource;
use App\Models\LineOfBusiness;
use Illuminate\Http\Request;

class LineOfBusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LineOfBusinessResource::collection(LineOfBusiness::limit(20)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LineOfBusinessRequest $request)
    {
        $business = LineOfBusiness::create($request->validated());

        return new LineOfBusinessResource($business);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(LineOfBusiness $business)
    {
        return new LineOfBusinessResource($business);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(LineOfBusinessRequest $request, Business $business)
    {
        $business->update($request->validated());

        return new LineOfBusinessResource($business);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(LineOfBusiness $business)
    {
        $business->delete();

        return response()->noContent();
    }
}
