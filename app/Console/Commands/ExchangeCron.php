<?php

namespace App\Console\Commands;

use App\Currency;
use App\ExchangeRate;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;
use GuzzleHttp\Client;

class ExchangeCron extends Command
{
    /**
     * Base currency for exchange API
     * @var string
     */
    protected $base_currency = 'USD';

    /**
     * Comparison currencies for exchange API
     * @var string
     */
    protected $currency_pairs = 'EUR,GBP';

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
    protected $description = 'Daily currency exchange API updates';

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
     * CronJob handler
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        \Log::info("Currency exchange cron is working fine!");
        \Log::info($this->getExchangeRates());
    }


    /**
     * Currency API getter
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getExchangeRates()
    {
        try {
            // initiate get request for currency API
            $client = new Client();
            $response_data = null;
            $res = $client->request('GET', 'https://api.exchangeratesapi.io/latest?base=' . $this->base_currency . '&symbols=' . $this->currency_pairs);
            $response_data = json_decode($res->getBody()->getContents());
            $resp_rates = (array)$response_data->rates;
            $currency_pairs_split = explode(',', $this->currency_pairs);
            foreach ($currency_pairs_split as $item)
            {
                $new_pair = ExchangeRate::updateOrCreate(['currency_from' => $this->base_currency, 'currency_to' => $item],['rate' => $resp_rates[$item], 'updated_at' => Carbon::now()->toDateTimeString()]);
            }
            return "Successfully updated currency_exchange_rates table";
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            $responseBodyAsString = json_decode($response->getBody()->getContents());
            return $responseBodyAsString;
        }
    }


}
