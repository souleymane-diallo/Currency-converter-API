<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): AnonymousResourceCollection
    {
        return CurrencyResource::collection(Currency::latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): CurrencyResource
    {
        $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string', 'max:3', 'unique:currencies,code'],
            'symbol' => ['nullable', 'min:1']
        ]);

        $currency = Currency::create([
            'name' => $request->name,
            'code' => $request->code,
            'symbol' => $request->symbol ?? '',
        ]);

        return new CurrencyResource($currency);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency): CurrencyResource
    {
        return CurrencyResource::make($currency);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency): CurrencyResource
    {
        $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string', 'min:3'],
            'symbol' => ['nullable', 'string', 'min:1'],
        ]);

        $currency->update($request->only(['name', 'code', 'symbol']));

        return new CurrencyResource($currency);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Currency $currency): Response
    {
        $currency->delete();

        return response()->noContent();
    }
}
