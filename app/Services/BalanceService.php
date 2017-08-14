<?php

namespace App\Services;

use App\Balance;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Exceptions\NotEnoughBalanceException;

class BalanceService
{
    public function charge($employee, $amount)
    {
        $currentBalance = $employee->balance();

        if ($currentBalance < $amount) {
            throw new NotEnoughBalanceException("Maaf, Balance tidak mencukupi.");
        }

        return Balance::payOrder($amount, $employee);
    }
}
