<?php

namespace App\Http\Controllers\Api\V1;

use App\Company;
use App\Balance;
use App\CompanyPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BalanceController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'company_id' => 'required',
            'amount_per_employee' => 'required|numeric',
        ]);

        $company = Company::find(request('company_id'));

        $employees = $company->activeEmployees();

        $employees->each(function ($employee) use ($company) {
            Balance::employeeTopUp($employee, request('amount_per_employee'));
        });

        CompanyPayment::create([
            'company_id' => $company->id,
            'employee_count' => $employees->count(),
            'amount_per_employee' => request('amount_per_employee'),
            'total_amount' => $employees->count() * request('amount_per_employee'),
            'note' => request('note'),
        ]);

        return response()->json([]);
    }
}
