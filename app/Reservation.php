<?php

namespace App;

class Reservation
{
    private $employee;

    private $meals;

    public function __construct($meals, $employee)
    {
        $this->meals = $meals;

        $this->employee = $employee;
    }

    public function totalCost()
    {
        return $this->meals->reduce(function ($total, $item) {
            return $total + $item->menu->final_price;
        }, 0);
    }

    public function complete($balanceService)
    {
        $balance = $balanceService->charge($this->employee, $this->totalCost());

        $order = Order::forMeals($this->meals, $this->employee, $this->totalCost());

        $balance->update([
            'description' => sprintf('Payment for order #%s', $order->id),
        ]);

        return $order;
    }

    public function cancel()
    {
        $this->meals->each->release();
    }
}
