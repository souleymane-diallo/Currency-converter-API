<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PairResource;
use App\Models\Currency;
use App\Models\Pair;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PairController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): AnonymousResourceCollection
    {

        return PairResource::collection(Pair::with('from', 'to', 'conversion')->latest()->get());
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

    // methode pour faire la conversion d'une devise à une autre
    public function convert($currency_from, $currency_to, $amount, $invert = false)
    {
        $codeFrom = Currency::where('code', $currency_from)->first();
        $codeTo = Currency::where('code', $currency_to)->first();

        if(isset($codeFrom) && isset($codeTo)) {
            $pair = Pair::with(['from', 'to', 'conversion'])
                ->where('from_id', $codeFrom->id)
                ->where('to_id', $codeTo->id)->first()
            ;
        } else {
            return response()->json([
                'success' => false,
                'message' => "Paire non trouvée"
            ], 404);
        }

        if($pair) {
            if ($invert == true) {
                $converted = round($amount * 1/$pair->rates) ;

                $conversion = DB::table('conversions')->insertGetId([
                    'pair_id' => $pair->id,
                ]);

                $data = [
                    'amount_currecy_from'   => $amount,
                    'from'                  => $currency_to,
                    'amount_currency_to'    => $converted,
                    'to'                    => $currency_from,
                    'conversion'            => $conversion
                ];
            } else {
                $converted = $amount * $pair->rates;

                $conversion = DB::table('conversions')->insertGetId([
                    'pair_id' => $pair->id,
                ]);

                $data = [
                    'amount_currency_from' => $amount,
                    'from'                 => $currency_from,
                    'amount_currency_to'   => $converted,
                    'to'                   => $currency_to,
                    'conversion'            => $conversion
                ];

            }
            return response()->json([
                'status' => true,
                'convert'=> $data,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Conversion impossible paire non trouvée'
            ], 404);
        }
    }
}
