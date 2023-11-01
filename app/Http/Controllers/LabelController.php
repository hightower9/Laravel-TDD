<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Http\Resources\LabelResource;
use App\Models\Label;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return LabelResource
     */
    public function index()
    {
        return LabelResource::collection(Label::all());
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param LabelRequest $request
     * @return LabelResource
     */
    public function store(LabelRequest $request)
    {
        return new LabelResource(Label::create($request->validated()));
    }

    /**
     * Display the specified resource.
     * 
     * @param Label $label
     * @return LabelResource
     */
    public function show(Label $label)
    {
        return new LabelResource($label);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param LabelRequest $request
     * @param Label $label
     * @return LabelResource
     */
    public function update(LabelRequest $request, Label $label)
    {
        $label->update($request->validated());

        return new LabelResource($label);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param Label $label
     * @return LabelResource
     */
    public function destroy(Label $label)
    {
        $label->delete();

        return (new LabelResource($label))->response()->setStatusCode(204);
    }
}
