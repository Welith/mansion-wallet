<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Http\Requests\CreateDepositRequest;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\Wallet;

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
     * Calculates comission fee based on parameters
     * @param $amount
     * @param $fee
     * @param $currency
     * @param $action
     * @return float|int
     */
    public function calcTransactionFee($amount, $fee, $currency, $action)
    {
        $usd_to_eur_rate = ExchangeRate::where(['currency_to' => 'EUR'])->first()->rate;
        $usd_to_gbp_rate = ExchangeRate::where(['currency_to' => 'GBP'])->first()->rate;
        $min_withdraw_fee_usd = \Config::get('constants.fees.fee_withdraw_min_eur') / $usd_to_eur_rate;
        $min_withdraw_fee_gbp = $min_withdraw_fee_usd * $usd_to_gbp_rate;
        $max_deposit_fee_usd = \Config::get('constants.fees.fee_deposit_max_eur') / $usd_to_eur_rate;
        $max_deposit_fee_gbp = $max_deposit_fee_usd * $usd_to_gbp_rate;
        $comission_fee = $amount * $fee;
        if ($currency == 'USD') {
            if ($action == 'deposit') {
                $comission_fee = ($comission_fee <= $max_deposit_fee_usd) ? $comission_fee : $max_deposit_fee_usd;
            } else if ($action == 'withdraw') {
                $comission_fee = ($comission_fee >= $min_withdraw_fee_usd) ? $comission_fee : $min_withdraw_fee_usd;
            }
        } else if ($currency == 'EUR') {
            if ($action == 'deposit') {
                $comission_fee = ($comission_fee <= \Config::get('constants.fees.fee_deposit_max_eur')) ? $comission_fee : \Config::get('constants.fees.fee_deposit_max_eur');
            } else if ($action == 'withdraw') {
                $comission_fee = ($comission_fee >= \Config::get('constants.fees.fee_withdraw_min_eur')) ? $comission_fee : \Config::get('constants.fees.fee_withdraw_min_eur');
            }
        } else if ($currency == 'GBP') {
            if ($action == 'deposit') {
                $comission_fee = ($comission_fee <= $max_deposit_fee_gbp) ? $comission_fee : $max_deposit_fee_gbp;
            } else if ($action == 'withdraw') {
                $comission_fee = ($comission_fee >= $min_withdraw_fee_gbp) ? $comission_fee : $min_withdraw_fee_gbp;
            }
        }
        return round((float)$comission_fee, 2);
    }

    /**
     * Currency conversion to USD
     * @param $amount
     * @param $rate
     * @return float
     */
    public function exchangeCurrencyToBase($amount, $rate)
    {
        $base_amount = $amount / $rate;
        return round((float)$base_amount, 2);
    }

    /**
     * Carries out transaction based on request path
     * @param CreateDepositRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function transaction(CreateDepositRequest $request)
    {
        $validator = \Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return redirect()->route($request->path())->withErrors($validator->errors());
        }
        $wallet = Wallet::with('user')->first();
        $transaction = TransactionType::where(['type' => $request->path()])->first();
        $deposit_trans = new Transaction();
        $deposit_trans->wallet_id = $wallet->id;
        $trans_type = $transaction->id;
        $deposit_trans->transaction_type = $trans_type;
        $transaction_fee = $transaction->fee;
        $deposit_trans->amount = $request->amount;
        $trans_currency_code = Currency::where(['id' => $request->currency])->first()->code;
        $deposit_trans->transaction_currency = $trans_currency_code;
        $deposit_trans->save();
        $comission_fee = $this->calcTransactionFee($request->amount, $transaction_fee, $trans_currency_code, $request->path());
        $actual_deposit = $request->amount - $comission_fee;
        if ($trans_currency_code == 'USD') {
            if ($request->path() == 'deposit') {
                $wallet->total_amount = $wallet->total_amount + $actual_deposit;
            } else if($request->path() == 'withdraw') {
                $wallet->total_amount = $wallet->total_amount - $actual_deposit;
            }
        } else {
            $exchange_rate_conv = ExchangeRate::where(['currency_to' => $trans_currency_code])->first()->rate;
            $base_currency_transaction = $this->exchangeCurrencyToBase($actual_deposit, $exchange_rate_conv);
            dd($base_currency_transaction);
            if ($request->path() == 'deposit') {
                $wallet->total_amount = $wallet->total_amount + $base_currency_transaction;
            } else if($request->path() == 'withdraw') {
                $wallet->total_amount = $wallet->total_amount - $base_currency_transaction;
            }
        }
        $wallet->save();
        if ($request->wantsJson()) {
            return response()->json(['comission_fee' => $comission_fee, 'requested_amount' => round((float)$request->amount, 2), 'total_balance' => $wallet->total_amount, 'status' => 'Created ' . $request->path() . ' successfully.']);
        }
        return redirect()->route('wallet')->with(['message' => 'Successfully made ' . $request->path() . '.']);
    }
}
