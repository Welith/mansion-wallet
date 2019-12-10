@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                Welcome to e-Wallet
            </div>
            <div class="card-body text-center text-justify font-weight-bold">
                @guest
                    Easy and convinient way to manage your savings
                    <div class="container" style="display: inline-block">
                        <a class="btn btn-lg btn-success" href="{{ route('login') }}">Login</a>or
                        <a class="btn btn-lg btn-primary" href="{{ route('register') }}">Register</a>
                    </div>
                @else
                    @if (!Auth::user()->hasWallet())
                        <div class="container m-4">
                            <span class="text-justify">{{$user->name}} I can see you are a new user. The first step to use this app is to create a wallet.<br>
                                As I am generous I am giving you 50 USD for free. Give your wallet a name and start making transactions (George's primary wallet).
                            </span>
                        </div>
                        <div class="text-center">
                            <a href="" class="btn btn-primary btn-rounded mb-4" data-toggle="modal"
                               data-target="#walletCreationForm" id="createWallet">Create wallet</a>
                        </div>
                        <div class="modal fade bg-dark" id="walletCreationForm" tabindex="-1" role="dialog"
                             aria-labelledby="walletLabel"
                             aria-hidden="true">
                            <div class="modal-dialog modal-notify modal-warning" role="document">
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title w-100 font-weight-bold">Choose a name for your
                                            wallet</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body mx-3 main-box">
                                        <div class="md-form mb-5">
                                            <input type="text" id="walletName" class="form-control validate">
                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button class="btn btn-default btn-primary" id="nameConfirm"
                                                data-redirect="{{route('wallet')}}"
                                                data-url="{{route('confirmWalletName')}}">Confirm
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection
