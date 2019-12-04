<?php

use Illuminate\Database\Seeder;

class TransactionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_types')->insert([
            'type' => 'deposit',
            'fee' => 0.03,
        ]);
        DB::table('transaction_types')->insert([
            'type' => 'withdraw',
            'fee' => 0.3,
        ]);
    }
}
