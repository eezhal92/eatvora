<?php

namespace App\Lib;

class Cost
{
    private $mealMenus;

    public function __construct($mealMenus)
    {
        $this->mealMenus = $mealMenus;
    }

    /**
     * Total amount of user order
     * @return int
     */
    public function total()
    {
        return $this->mealMenus->map(function ($menu) {
            return $menu->final_price;
        })->sum();
    }

    /**
     * Amount should be paid to vendor
     * @return int
     */
    public function vendorBill()
    {
        return $this->mealMenus->map(function ($menu) {
            return $menu->price;
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
