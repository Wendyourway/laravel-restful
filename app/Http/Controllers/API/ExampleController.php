<?php

namespace App\Http\Controllers\API;

use App\Models\Example;
use \Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExampleIndexRequest;
use App\Http\Requests\ExampleStoreRequest;
use App\Http\Requests\ExampleShowRequest;
use App\Http\Requests\ExampleUpdateRequest;
use App\Http\Requests\ExampleDestroyRequest;

/**
 * @group Example
 * 
 * Example API Endpoints
 */
class ExampleController extends Controller
{
    /**
     * Index Example Route
     * 
     * Display a listing of the example resource.
     *
     * @param App\Http\Requests\ExampleIndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(ExampleIndexRequest $request): JsonResponse
    {
        $example = Example::get();
        return response()->json($example);
    }

    /**
     * Store Example Endpoint
     * 
     * Store a newly created example resource in storage.
     *
     * @param  App\Http\Requests\ExampleStoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ExampleStoreRequest $request): JsonResponse
    {
        $example = new Example;
        $example->param1 = $request->param1;
        $example->param2 = $request->param2;
        if($example->save()) {
            return response()->json('store success');
        }
    }

    /**
     * Show Example Endpoint
     * 
     * Display the specified example resource.
     *
     * @param App\Http\Requests\ExampleShowRequest $request
     * @param  App\Models\Example  $example
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ExampleShowRequest $request, Example $example): JsonResponse
    {
        return response()->success($example);
    }

    /**
     * Update Example Endpoint
     * 
     * Update the specified example resource in storage.
     *
     * @param  App\Http\Requests\ExampleUpdateRequest  $request
     * @param  App\Models\Example  $example
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExampleUpdateRequest $request, Example $example): JsonResponse
    {
        $example->param1 = $request->param1;
        $example->param2 = $request->param2;
        if($example->save()) {
            return response()->success($example);
        }
    }

    /**
     * Destroy Example Endpoint
     * 
     * Remove the specified resource from storage.
     *
     * @param App\Http\Requests\ExampleDestroyRequest $request
     * @param  App\Models\Example  $example
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ExampleDestroyRequest $request, Example $example): JsonResponse
    {
        if($example->delete()) {
            return response()->success('success');
        }
    }
}
