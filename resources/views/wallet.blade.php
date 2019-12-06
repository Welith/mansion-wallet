@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card text-center">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="card-header">
                Hello, {{$user->name}}
            </div>
            <div class="card-body">
                <h5 class="card-title text-center">Your Funds: {{$user_wallet->total_amount}} {{$wallet_currency_code}}</h5>
                <p class="card-text text-center"> You have two options available: </p>
                <div class="btn-group text-center">
                    <a class="btn btn-success" href="{{route('deposit')}}">Deposit</a>
                    <a class="btn btn-info" href="{{route('withdraw')}}">Withdraw</a>
                </div>
            </div>
        </div>
    </div>
@endsection