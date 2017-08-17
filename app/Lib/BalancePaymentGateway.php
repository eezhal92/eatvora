<?php

namespace App\Lib;

use App\Balance;
use App\Exceptions\NotEnoughBalanceException;

class BalancePaymentGateway
{
    private $charges;

    private $beforeFirstChargeCallback;

    public function __construct()
    {
        $this->charges = collect();
    }

    public function beforeFirstCharge($callback)
    {
        $this->beforeFirstChargeCallback = $callback;
    }

    public function charge($employee, $amount)
    {
        if ($this->beforeFirstChargeCallback !== null) {
            $callback = $this->beforeFirstChargeCallback;
            $this->beforeFirstChargeCallback = null;
            $callback($this);
        }

        $currentBalance = $employee->balance();

        if ($currentBalance < $amount) {
            throw new NotEnoughBalanceException("Maaf, Balance tidak mencukupi.");
        }

        $this->charges[] = (object) ['amount' => $amount];

        return Balance::payOrder($amount, $employee);
    }

    public function totalCharges()
    {
        return $this->charges->map->amount->sum();
    }
}
