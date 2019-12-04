<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            'currency' => 'United States Dollar',
            'code' => 'USD',
        ]);
        DB::table('currencies')->insert([
            'currency' => 'Great Britain Pound',
            'code' => 'GBP',
        ]);
        DB::table('currencies')->insert([
            'currency' => 'European Monetary Unit',
            'code' => 'EUR',
        ]);
    }
}
