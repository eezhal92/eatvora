<?php

namespace App\Lib;

interface Claimable
{
    public function claimFor($order);
}
