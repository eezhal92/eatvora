<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'office_id' => 'required|integer',
        ]);

        $officeId = $request->get('office_id');

        $employees = User::join('employees', 'users.id', '=', 'employees.user_id')
            ->join('offices', 'employees.office_id', '=', 'offices.id')
            ->select('users.*')
            ->where('offices.id', $officeId)
            ->get();

        return $employees;
    }
}
