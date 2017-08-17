<?php

use App\User;
use App\Office;
use App\Employee;

class EmployeeFactory
{
    public static function createOne($company, $user)
    {
        $office = factory(Office::class)->create(['company_id' => $company->id]);

        if (is_array($user)) {
            $user = factory(User::class)->create($user);
        }

        return factory(Employee::class)->create(['office_id' => $office->id, 'user_id' => $user->id]);
    }

    public static function createEmployees($company, $count = 1)
    {
        $office = factory(Office::class)->create(['company_id' => $company->id]);

        return factory(Employee::class, $count)->create(['office_id' => $office->id]);
    }
}
