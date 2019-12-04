<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_exchange_rates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('currency_from')->nullable();
            $table->foreign('currency_from')->references('id')->on('currencies')->onDelete('cascade');
            $table->unsignedBigInteger('currency_to')->nullable();
            $table->foreign('currency_to')->references('id')->on('currencies')->onDelete('cascade');
            $table->unsignedDecimal('rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency_exchange_rates');
    }
}
