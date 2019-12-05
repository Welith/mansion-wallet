<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletStoreRequest;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('landing-page');
    }

    /**
     * Store the initial wallet information
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(WalletStoreRequest $request)
    {
        $wallet = new Wallet();
        $validator = \Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            if($request->ajax())
            {
                return response()->json(array(
                    'errors' => $validator->errors()
                ), 422);
            }
        }
        $wallet->name = $request->name;
        $wallet->user_id = \Auth::user()->id;
        $wallet->total_amount = 50;
        $wallet->currency = 1;
        $wallet->save();
        return response()->json(['success' => 'Wallet was successfully added.']);

    }
}
