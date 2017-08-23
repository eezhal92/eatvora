<?php

namespace App;

trait MenuConsent
{
    public function getFinalPriceAttribute()
    {
        // @todo add test
        $commissionPercentage = config('eatvora.commission_percentage');

        return $this->roundUpByPerPoint($this->price + ($this->price * $commissionPercentage));
    }

    public function getPointAttribute()
    {
        return $this->final_price / config('eatvora.rupiah_per_point');
    }

    public function formattedPrice()
    {
        return sprintf('Rp. %s', number_format($this->price));
    }

    public function formattedFinalPrice()
    {
        // @todo add test
        return sprintf('Rp. %s', number_format($this->final_price));
    }

    private function roundUpByPerPoint($price)
    {
        if ($this->isMultipleOfRupiahPerPoint($price)) {
            return $price;
        }

        return $this->roundUpToNearestMultiply($price, config('eatvora.rupiah_per_point'));
    }

    private function roundUpToNearestMultiply($price, $multiply)
    {
        return ceil($price / $multiply) * $multiply;
    }

    private function isMultipleOfRupiahPerPoint($price)
    {
        return $price % config('eatvora.rupiah_per_point') === 0;
    }
}
