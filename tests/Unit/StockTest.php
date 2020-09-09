<?php

namespace Tests\Unit;

use App\Clients\Client;
use App\Clients\StockStatus;
use Facades\App\Clients\ClientFactory;
use App\Retailer;
use App\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        // 而且可以指定回傳值
        ClientFactory::shouldReceive('make')->andReturn(new FakeClient);

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
