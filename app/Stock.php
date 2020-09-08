<?php

namespace App;

use App\Clients\BestBuy;
use App\Clients\Target;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Stock extends Model
{
    protected $table = 'stock';

    protected $guarded = [];

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        $class = "App\\Clients\\" . Str::studly($this->retailer->name);
        
        $status = (new $class)->checkAvailability($this);
        
        $this->update([
            'price' => $status->price,
            'in_stock' => $status->available
        ]);
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}
