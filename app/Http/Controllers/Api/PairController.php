<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PairResource;
use App\Models\Pair;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): AnonymousResourceCollection
    {

        return PairResource::collection(Pair::with('from', 'to')->latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): PairResource
    {
        $request->validate([
            'from_id' => ['required','numeric'],
            'to_id' => ['required', 'numeric'],
            'rates' => ['required' ]
        ]);

        $pair = Pair::create([
            'from_id' => $request->from_id,
            'to_id' => $request->to_id,
            'rates' => $request->rates
        ]);

        return new PairResource($pair);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pair  $pair
     * @return \Illuminate\Http\Response
     */
    public function show(Pair $pair): PairResource
    {
        return PairResource::make($pair);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pair  $pair
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pair $pair): PairResource
    {
        $request->validate([
            'from_id' => 'required|numeric',
            'to_id' => 'required|numeric',
            'rates' => 'required'
        ]);

        $pair->update($request->only(['from_id', 'to_id', 'rates']));

        return new PairResource($pair);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pair  $pair
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pair $pair): Response
    {
        $pair->delete();

        return response()->noContent();
    }

    public function convert(Request $request) {
        
    }
}
