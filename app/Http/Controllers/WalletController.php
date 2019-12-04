<?php

namespace App\Http\Controllers;

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
    public function store(Request $request)
    {
        try {
            $wallet = new Wallet();
            $request->validate([
                'name' => 'required|min:3|max:255|string'
            ]);
            $wallet->name = $request->name;
            $wallet->user_id = \Auth::user()->id;
            $wallet->total_amount = 50;
            $wallet->currency = 1;
            $wallet->save();
            return response()->json(['success' => 'Data is successfully added']);
        } catch (ValidationException $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
