<?php

namespace Tests\Unit;

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
}
