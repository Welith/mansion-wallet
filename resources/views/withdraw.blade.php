@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card text-center">
            <div class="card-header">
                Withdraw Transaction
            </div>
            <div class="card-body">
                <div class="text-center m-4">
                    <a class="btn btn-success" href="{{route('deposit')}}">Deposit</a>
                    <a class="btn btn-info" href="{{route('wallet')}}">Wallet</a>
                </div>
                <h5 class="card-title text-center"> You can withdraw amounts in the following currencies EUR, USD and
                    GBP. </h5>
                <div class="card-text text-center"> Please note that there is a withdraw fee of {{$withdraw_fee}}%. <br>
                    Also, the base currency for this wallet is USD, meaning that other currencies have a conversion rate
                    applied:
                    <ul class="no-list-style">
                        @if(count($exchange_rates))
                            @foreach($exchange_rates as $rate)
                                <li>{{$rate->currency_from}} -> {{$rate->currency_to}} -> {{$rate->rate}}</li>
                            @endforeach
                        @else
                            <li>USD -> EUR -> 0.901388</li>
                            <li>USD -> GBP -> 0.761403</li>
                        @endif
                    </ul>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-2">
                        <label for="amount_req">Amount</label>
                        <input type="number" min="0" step="0.01" class="form-control" id="amount_req" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="currency_select">Currency</label>
                        @if(count($currencies))
                            <select class="custom-select" id="currency_select" required>
                                <option selected>Choose...</option>
                                @foreach($currencies as $currency)
                                    <option value="{{$currency->code}}">{{$currency->code}}</option>
                                @endforeach
                                @else
                                    <option value="EUR">EUR</option>
                                    <option value="USD">USD</option>
                                    <option value="GBP">GBP</option>
                                @endif
                            </select>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Confirm Withdraw</button>
            </div>
        </div>
    </div>
@endsection
