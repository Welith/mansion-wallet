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
Route::get('/{wallet?}', ['as' => 'wallet', 'uses' => 'WalletController@index']);
Route::post('/wallet/create', 'WalletController@store')->name('confirmWalletName');
Route::post('/deposit', 'TransactionController@storeTransaction')->name('depositTransaction');
Route::post('/withdraw', 'TransactionController@storeTransaction')->name('withdrawTransaction');
