<?php

namespace App\Clients;

use App\Retailer;
use Illuminate\Support\Str;

class ClientFactory
{
    public function make(Retailer $retailer)
    {
        $class = "App\\Clients\\" . Str::studly($retailer->name);
        
        if (!class_exists($class)) {
            throw new \Exception('Client not found for ' . $this->retailer->name);
        }

        return new $class;
    }
}