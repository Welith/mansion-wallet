@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card text-center">
            <div class="card-header">
                Withdraw Transaction
            </div>
            <div class="card-body">
                @if($errors)
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show">{{ $error }}</div>
                    @endforeach
                @endif
                @if(session()->has('message'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session()->get('message') }}
                    </div>
                @endif
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
                <form action="{{route('withdrawTransaction')}}" method="POST">
                    <div class="form-row">
                        {{csrf_field()}}
                        <div class="col-md-6 mb-2">
                            <label for="amount_req">Amount</label>
                            <input type="number" min="0" step="0.01" class="form-control" id="amount_req" name="amount"
                                   required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="currency_select">Currency</label>
                            @if(count($currencies))
                                <select class="custom-select" id="currency_select" name="currency" required>
                                    <option value="" selected>Choose...</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{$currency->id}}">{{$currency->code}}</option>
                                    @endforeach
                                    @else
                                        <option value="EUR">EUR</option>
                                        <option value="USD">USD</option>
                                        <option value="GBP">GBP</option>
                                    @endif
                                </select>
                        </div>
                    </div>
                    <button class="btn btn-success" type="submit" role="button">Confirm Withdraw</button>
                </form>
            </div>
        </div>
    </div>
@endsection
