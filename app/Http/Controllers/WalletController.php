<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Http\Requests\WalletStoreRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;

/**
 * Class WalletController
 * @package App\Http\Controllers
 */
class WalletController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = \Auth::user();
        if ($user->hasWallet()) {
            $user = \Auth::user();
            $user_wallet = Wallet::where(['user_id' => $user->id])->first();
            $wallet_currency_code = Currency::where(['code' => $user_wallet->currency])->first()->code;
            return view('wallet', compact('user_wallet', 'wallet_currency_code', 'user'));
        }
        return view('landing-page', compact('user'));
    }

    /**
     * Store the initial wallet information
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(WalletStoreRequest $request)
    {
        // Validate input
        $validator = \Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            if($request->ajax())
            {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
        }
        // Store initial walllet state
        $wallet = new Wallet();
        $wallet->name = $request->name;
        $wallet->user_id = \Auth::user()->id;
        $wallet->total_amount = 50;
        $wallet->currency = 'USD';
        $wallet->save();
        return response()->json(['success' => 'Wallet was successfully added.', 'data' => $request->toArray()]);
    }
}
