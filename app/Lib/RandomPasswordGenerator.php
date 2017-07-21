<?php

namespace App\Lib;

class RandomPasswordGenerator
{
    public function generate()
    {
        return str_random(8);
    }
}
