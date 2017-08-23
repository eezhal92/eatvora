<?php

namespace App;

use App\Lib\Cost;

class Reservation
{
    private $employee;

    private $cart;

    private $meals;

    private $cost;

    public function __construct($cart, $meals, $employee)
    {
        $this->cart = $cart;

        $this->meals = $meals;

        $this->employee = $employee;

        // dd($this->meals->sum('final_price'));

        $this->cost = new Cost($this->meals);
    }

    public function totalCost()
    {
        return $this->cost->total();
    }

    public function complete($balanceService)
    {
        $balance = $balanceService->charge($this->employee, $this->totalCost());

        $order = Order::forMeals($this->meals, $this->employee, $this->cost);

        $balance->update(['description' => sprintf('Payment for order #%s', $order->id)]);

        $this->cart->update(['order_id' => $order->id]);

        return $order;
    }

    public function cancel()
    {
        $this->meals->each->release();
    }
}
