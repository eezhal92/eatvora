<?php

use App\Office;
use App\Employee;

class EmployeeFactory
{
    public static function createEmployees($company, $count = 1)
    {
        $office = factory(Office::class)->create(['company_id' => $company->id]);

        return factory(Employee::class, $count)->create(['office_id' => $office->id]);
    }
}
