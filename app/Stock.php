<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';

    protected $guarded = [];

    protected $casts = [
        'in_stock' => 'boolean'
    ];
}
