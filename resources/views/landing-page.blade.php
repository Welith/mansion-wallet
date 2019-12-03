@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                Welcome to my test e-Wallet
            </div>
            <div class="card-body text-center text-justify font-weight-bold">
                You have two options:
                @guest
                    <div class="container" style="display: inline-block">
                        <a class="btn btn-lg btn-primary" href="{{ route('login') }}">Login</a>or
                        <a class="btn btn-lg btn-danger" href="{{ route('register') }}">Register</a>
                    </div>
                @else
                    @if (Auth::user()->hasWallet())
                        <div class="container" style="display: inline-block">
                            <a class="btn btn-lg btn-primary" href="{{ route('login') }}">Deposit</a> or
                            <a class="btn btn-lg btn-danger" href="{{ route('register') }}">Withdraw</a>
                        </div>
                        Choose wisely ! :)
                    @else
                        <div class="container">
                            <a class="btn btn-lg btn-primary" href="#">Create a wallet</a>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection
