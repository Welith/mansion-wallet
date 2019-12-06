<?php

namespace App\Http\Controllers;

use App\Currency;
use App\ExchangeRate;
use App\Http\Requests\CreateDepositRequest;
use App\Transaction;
use App\TransactionType;
use App\Wallet;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $exchange_rates;
    protected $currencies;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->exchange_rates = ExchangeRate::all();
        $this->currencies = Currency::all();
    }

    /**
     * Deposit index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function depositView()
    {
        $deposit_fee = TransactionType::where(['type' => 'deposit'])->first()->fee;
        $exchange_rates = $this->exchange_rates;
        $currencies = $this->currencies;
        return view('deposit', compact(['deposit_fee', 'exchange_rates', 'currencies']));
    }

    /**
     * withdraw index
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function withdrawView()
    {
        $withdraw_fee = TransactionType::where(['type' => 'withdraw'])->first()->fee;
        $exchange_rates = $this->exchange_rates;
        $currencies = $this->currencies;
        return view('withdraw', compact(['withdraw_fee', 'exchange_rates', 'currencies']));
    }

    /**
     * @param CreateDepositRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function depositTransaction(CreateDepositRequest $request)
    {
        $validator = \Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return redirect()->route('deposit')->withErrors($validator->errors());
        }
        $wallet = Wallet::with('user')->first();
        $deposit_trans = new Transaction();
        $deposit_trans->wallet_id = $wallet->id;
        $trans_type = TransactionType::where(['type' => 'deposit'])->first()->id;
        $deposit_trans->transaction_type = $trans_type;
        $transaction_fee = TransactionType::where(['type' => 'deposit'])->first()->fee;
        $deposit_trans->amount = $request->amount;
        $trans_currency = Currency::where(['id' => $request->currency])->first()->code;
        $deposit_trans->transaction_currency = $trans_currency;
        $deposit_trans->save();
        $comission_fee = $request->amount * $transaction_fee;
        $actual_deposit = $request->amount - $comission_fee;
        if ($request->currency == 'USD') {
            $wallet->total_amount = $wallet->total_amount + $actual_deposit;
        } else {
            $exchange_rate_conv = ExchangeRate::where(['currency_to' => $trans_currency])->first()->rate;
            $base_currency_deposit = $actual_deposit / $exchange_rate_conv;
            $wallet->total_amount = $wallet->total_amount + $base_currency_deposit;
        }
        $wallet->save();
        if($request->wantsJson()) {
            return response()->json(['comission_fee' => $comission_fee, 'requested_amount' => (int)$request->amount, 'total_balance' => $wallet->total_amount, 'status' => 'OK']);
        }
        return redirect()->route('wallet')->with(['message' => 'Successfully added transaction']);
    }
}
