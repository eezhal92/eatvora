<?php

namespace App\Lib;

class Cost
{
    private $meals;

    public function __construct($meals)
    {
        $this->meals = $meals;
    }

    /**
     * Total amount of user order
     * @return int
     */
    public function total()
    {
        return $this->meals->map(function ($meal) {
            return $meal->final_price;
        })->sum();
    }

    /**
     * Amount should be paid to vendor
     * @return int
     */
    public function vendorBill()
    {
        return $this->meals->map(function ($meal) {
            return $meal->price;
        })->sum();
    }

    /**
     * Eatvora revenue
     * @return int
     */
    public function revenue()
    {
        return $this->total() - $this->vendorBill();
    }
}
