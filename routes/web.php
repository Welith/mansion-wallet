<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/deposit', 'TransactionController@depositView')->name('deposit');
Route::get('/withdraw', 'TransactionController@withdrawView')->name('withdraw');
Route::get('/{wallet?}',  'WalletController@index')->name('wallet');
Route::post('/wallet/create', 'WalletController@storeWallet')->name('confirmWalletName');
Route::post('/deposit', 'TransactionController@storeTransaction')->name('depositTransaction');
Route::post('/withdraw', 'TransactionController@storeTransaction')->name('withdrawTransaction');
Route::post('/edit/wallet-name', 'WalletController@editWallet')->name('editWalletName');
