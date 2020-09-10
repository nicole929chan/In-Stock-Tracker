<?php

namespace Tests\Unit;

use App\Clients\Client;
use App\Clients\StockStatus;
use Facades\App\Clients\ClientFactory;
use App\Retailer;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use RetailerWithProductSeeder;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_throws_an_exception_if_a_client_is_not_found_when_tracking()
    {
        $this->seed(RetailerWithProductSeeder::class);

        Retailer::first()->update(['name' => 'Foo Retailer']);

        $this->expectException(\Exception::class);

        Stock::first()->track();
    }

    public function test_it_updates_local_stock_status_after_being_tracked()
    {
        $this->seed(RetailerWithProductSeeder::class);

        // 測試程式碼執行中如果遇到 real-time ClientFactory Facade
        // 就自動轉換為「模擬物件」
        // 而且指定回傳值為自訂義的物件(FakeClient)
        // FakeClient必須定義checkAvailability()
        // 因為Stock程式碼對回傳的Client有呼叫checkAvailability()
        ClientFactory::shouldReceive('make')->andReturn(new FakeClient);

        // real-time ClientFactory facade 執行後需要回傳一個物件(模擬為$client)
        // $client可以再自訂模擬必須執行checkAvailability()並且回傳StockStatus物件
        // 而Stock也真的有執行checkAvailability()
        // 這裡稍微難理解
        // $client = Mockery::mock(Client::class);
        // $client->shouldReceive('checkAvailability')
        //     ->andReturn(new StockStatus($available = true, $price = 9900));
        // ClientFactory::shouldReceive('make')->andReturn($client);

        // 縮寫上述的語法
        // make會回來另一個模擬物件
        // ClientFactory::shouldReceive('make->checkAvailability')
        //     ->andReturn(new StockStatus($available = true, $price = 9900));

        $stock = tap(Stock::first())->track();

        $this->assertTrue($stock->in_stock);
    }
}

class FakeClient implements Client
{
    public function checkAvailability(Stock $stock)
    {
        return new StockStatus($available = true, $price = 9900);
    }
}
