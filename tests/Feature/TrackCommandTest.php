<?php

namespace Tests\Feature;

use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use RetailerWithProductSeeder;
use Tests\TestCase;

class TrackCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_tracks_product_stock()
    {
        $this->seed(RetailerWithProductSeeder::class);

        $this->assertFalse(Product::first()->inStock());

        // 模擬API
        Http::fake(function () {
            return [
                'price' => 29900,
                'available' => true
            ];
        });
        
        $this->artisan('track');
        
        $this->assertTrue(Product::first()->inStock());
    }
}
