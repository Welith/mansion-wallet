<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wallet_id')->nullable();
            $table->foreign('wallet_id')->references('id')->on('user_wallets')->onDelete('cascade');
            $table->unsignedBigInteger('transaction_type')->nullable();
            $table->foreign('transaction_type')->references('id')->on('transaction_types')->onDelete('cascade');
            $table->unsignedBigInteger('transaction_currency')->nullable();
            $table->foreign('transaction_currency')->references('id')->on('currencies')->onDelete('cascade');
            $table->unsignedDecimal('amount', 8, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
