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
}