<?php

namespace Tests\Clients;

use App\Clients\BestBuy;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RetailerWithProductSeeder;
use Tests\TestCase;

class BestBuyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_tracks_a_product()
    {
        $this->seed(RetailerWithProductSeeder::class);

        $stock = tap(Stock::first())->update([
            'sku' => '6364253',
            'url' => 'https://www.bestbuy.com/site/nintendo-switch-32gb-console-gray-joy-con/6364253?skuId=6364253'
        ]);

        try {
            $stockStatus = (new BestBuy)->checkAvailability($stock);
        } catch (\Exception $e) {
            throw $e;
        }

        // 無法註冊BestBuy的開發者帳號
        // 不測試了
        // $this->assertTrue(true);    
    }
}