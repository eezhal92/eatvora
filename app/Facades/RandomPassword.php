<?php

namespace App\Facades;

use App\Lib\RandomPasswordGenerator;
use Illuminate\Support\Facades\Facade;

class RandomPassword extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RandomPasswordGenerator::class;
    }
}
