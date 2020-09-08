<?php

namespace App;

use App\Clients\BestBuy;
use App\Clients\Target;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $guarded = [];

    protected $casts = [
        'in_stock' => 'boolean'
    ];

    public function track()
    {
        if ($this->retailer->name == 'Best Buy') {
            $results = (new BestBuy())->checkAvailability($this);
        }

        if ($this->retailer->name == 'Target') {
            $results = (new Target())->checkAvailability($this);
        }

        $this->update([
            'price' => $results['price'],
            'in_stock' => $results['available']
        ]);
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}
