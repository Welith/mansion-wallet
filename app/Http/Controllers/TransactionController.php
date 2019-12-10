<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Http\Requests\CreateTransactionRequest;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\Wallet;

/**
 * Class TransactionController
 * @package App\Http\Controllers
 */
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
     * Carries out transaction based on request path
     * @param CreateTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function storeTransaction(CreateTransactionRequest $request)
    {
        $validator = \Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return redirect()->route($request->path())->with(['error' => $validator->errors()]);
        }
        $wallet = Wallet::with('user')->where(['user_id' => \Auth::user()->id])->first();
        $db_transaction_type = TransactionType::where(['type' => $request->path()])->first();
        $transaction = new Transaction();
        $transaction->wallet_id = $wallet->id;
        $transaction->transaction_type = $db_transaction_type->id;
        $transaction_fee = $db_transaction_type->fee;
        $transaction->amount = $request->amount;
        $trans_currency_code = Currency::where(['id' => $request->currency])->first()->code;
        $transaction->transaction_currency = $trans_currency_code;
        $comission_fee = Transaction::calcTransactionFee($request->amount, $transaction_fee, $trans_currency_code, $request->path());
        $actual_trans_amount = $request->amount - $comission_fee;
        $exchange_rate_conv = ($trans_currency_code == 'USD') ? 1 : ExchangeRate::where(['currency_to' => $trans_currency_code])->first()->rate;
        $base_currency_transaction = ExchangeRate::exchangeCurrencyToBase($actual_trans_amount, $exchange_rate_conv);
        if ($request->path() == 'deposit') {
            $wallet->total_amount = $wallet->total_amount + $base_currency_transaction;
        } else if ($request->path() == 'withdraw') {
            if ($wallet->total_amount >= $base_currency_transaction) {
                $wallet->total_amount = $wallet->total_amount - $base_currency_transaction;
            } else {
                return redirect()->route($request->path())->with(['message' => 'Insufficient funds. Please check your funds.']);
            }
        }
        $transaction->save();
        $wallet->save();
        if ($request->wantsJson()) {
            return response()->json(['comission_fee' => round((float)$comission_fee, 2), 'requested_amount' => round((float)$request->amount, 2), 'total_balance' => round((float)$wallet->total_amount, 2), 'status' => 'Created ' . $request->path() . ' successfully.'], 302);
        }
        return redirect()->route('wallet')->with(['message' => 'Successfully made ' . $request->path() . '.']);
    }
}
