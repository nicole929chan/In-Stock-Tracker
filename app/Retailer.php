<?php

namespace App;

use App\Clients\ClientFactory;
use Illuminate\Database\Eloquent\Model;

class Retailer extends Model
{
    protected $guarded = [];

    public function client()
    {
        return (new ClientFactory)->make($this);
    }

    public function addStock(Product $product, Stock $stock)
    {
        $stock->product_id = $product->id;
        
        $this->stock()->save($stock);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
