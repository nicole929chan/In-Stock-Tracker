<?php

namespace App\Clients;

use App\Stock;
use Illuminate\Support\Facades\Http;

class BestBuy implements Client
{
    public function checkAvailability(Stock $stock)
    {
        $results = Http::get('http:://foo.test')->json();

        return new StockStatus(
            $results['available'],
            $results['price']
        );
    }

    protected function endpoint($sku)
    {
        $key = config('services.clients.bestBuy.key');

        return "https://api.bestbuy.com/v1/products/{$sku}.json?apiKey={$key}";
    }
}