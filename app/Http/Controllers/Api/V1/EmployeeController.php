<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use App\Office;
use App\Employee;
use Illuminate\Http\Request;
use App\Facades\RandomPassword;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeRegistrationEmail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request, [
            'office_id' => 'required|integer',
        ]);

        $limit = $request->get('limit', 20);
        $page = $request->get('page', 1);
        $offset = ($page * $limit) - $limit;

        $officeId = $request->get('office_id');

        $query = User::join('employees', 'users.id', '=', 'employees.user_id')
            ->join('offices', 'employees.office_id', '=', 'offices.id')
            ->select(\DB::raw('employees.id as id'), 'users.name', 'users.email', 'employees.active', 'employees.created_at')
            ->where('offices.id', $officeId);

        if ($q = $request->get('query')) {
            $query->where('users.name', 'like', "%{$q}%")
                ->orWhere('users.email', 'like', "%{$q}%")
                ->where('employees.office_id', $officeId)
                ->distinct();
        }

        $totalEmployees = $query->count();

        $employees = $query
            ->limit($limit)
            ->skip($offset)
            ->get();

        $pageCount = ceil($totalEmployees / $limit);

        return response()->json([
            'total_records' => $totalEmployees,
            'current_page' => (int) $page,
            'page_count' => $pageCount,
            'employees' => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
        ]);

        try {
            $office = Office::with('company')->findOrFail($request->get('office_id'));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'office_id' => ['Office is not exists'],
            ], 422);
        }

        $randomTemporaryPassword = RandomPassword::generate();

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($randomTemporaryPassword),
        ]);

        $employee = Employee::create([
            'office_id' => $request->get('office_id'),
            'user_id' => $user->id,
        ]);

        Mail::to($user->email)->send(new EmployeeRegistrationEmail($office->company, $user, $randomTemporaryPassword));

        // @todo: add assertion in test
        $employee = array_merge($user->toArray(), $employee->toArray());

        return response()->json($employee, 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::with('user')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Employee was not found.',
            ], 404);
        }

        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => [
                'required',
                Rule::unique('users')->ignore($employee->user_id),
            ],
        ]);

        $employee->user->update([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
        ]);

        // @todo: add assertion in test
        $employee = array_merge($employee->user->toArray(), $employee->toArray());

        return response()->json($employee, 200);
    }

    public function updateActive(Request $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([], 404);
        }

        $employee->update([
            'active' => $request->get('status'),
        ]);

        return response()->json([], 200);
    }
}
