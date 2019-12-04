<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class ExchangeCron extends Command
{
    /**
     * Base currency for exchange API
     * @var string
     */
    protected $base = 'USD';

    /**
     * Comparison currencies for exchange API
     * @var string
     */
    protected $symbols = 'EUR,GBP';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info("Currency exchange cron is working fine!");

        $test = $this->getExchangeRates();

        \Log::info(print_r($test, true));
    }


    public function getExchangeRates()
    {
        $client = new Client();
        $response_data = null;
        $res = $client->request('GET', 'https://api.exchangeratesapi.io/latest?base=' . $this->base . '&symbols=' . $this->symbols);
        $symbols_split = explode(',', $this->symbols);
        if ($res->getStatusCode() == 200) { // 200 OK
            $rates = \DB::table('currency_exchange_rates')->get()->all();
            $response_data = json_decode($res->getBody()->getContents());
            foreach ($response_data->rates as $rate) {
                if (!$rates) {
                    // insert
                    \DB::table('currency_exchange_rates')->insert([
                        ['currency_from' => $this->base, 'currency_to' => $symbols_split[0], 'rate' => $response_data->rates->$symbols_split[0]],
                        ['currency_from' => $this->base, 'currency_to' => $symbols_split[1], 'rate' => $response_data->rates->$symbols_split[1]],
                    ]);
                } else {
                    // Update rates
                    \DB::table('currency_exchange_rates')->where([])
            }

            }

        }
        return $response_data->rates[0];
    }


}
