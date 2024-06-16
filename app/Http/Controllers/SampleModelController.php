<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSampleModelRequest;
use App\Http\Requests\UpdateSampleModelRequest;
use App\Http\Resources\SampleModelResource;
use App\Models\SampleModel;

class SampleModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->success(SampleModelResource::collection(SampleModel::all()));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSampleModelRequest $request)
    {
        $data = $request->validated();
        $sample_model = SampleModel::create($data);
        return response()->success(new SampleModelResource($sample_model));
    }

    /**
     * Display the specified resource.
     */
    public function show(SampleModel $sample_model)
    {
        return response()->success(new SampleModelResource($sample_model));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSampleModelRequest $request, SampleModel $sample_model)
    {
        $data = $request->validated();
        $sample_model->update($data);
        return response()->success(new SampleModelResource($sample_model));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SampleModel $sample_model)
    {
        $sample_model->delete();
        return response()->deleted();
    }
}
