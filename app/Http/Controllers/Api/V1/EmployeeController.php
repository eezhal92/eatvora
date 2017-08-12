<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use App\Office;
use App\Company;
use App\Employee;
use App\Jobs\CreateEmployee;
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
            ->select(\DB::raw('employees.id as id'), 'users.name', 'users.email', 'employees.active', 'employees.created_at', 'employees.office_id')
            ->where('offices.id', $officeId)
            ->whereNull('employees.deleted_at');

        if ($q = $request->get('query')) {
            $query->where(function ($nestedQuery) use ($q) {
                    $nestedQuery->where('users.name', 'like', "%{$q}%")
                        ->orWhere('users.email', 'like', "%{$q}%");
                })
                ->where('employees.office_id', $officeId)
                ->distinct();
        }

        $totalEmployees = $query->count();

        $employees = $query
            ->limit($limit)
            ->skip($offset)
            ->get();

        $pageCount = ceil($totalEmployees / $limit);

        // dd($employees);

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

    public function bulkStore(Request $request)
    {
        $this->validate($request, [
            'office_id' => 'required',
        ]);

        if (!$request->file('file')) {
            return response()->json([], 422);
        }

        $office = Office::findOrFail($request->get('office_id'));

        $file = request()->file('file');

        \Excel::load($file, function ($reader) use ($request) {
            $res = $reader->get();

            foreach ($res as $cell) {
                $tempPassword = RandomPassword::generate();

                dispatch(new CreateEmployee($cell['name'], $cell['email'], $tempPassword, $request->get('office_id')));
            }

        });

        return redirect('/ap/companies/' . $office->company_id);
    }

    public function update(Request $request, $id)
    {
        try {
            $employee = Employee::with('user', 'office.company')->findOrFail($id);
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
            'office_id' => 'sometimes|numeric',
        ]);

        if ($officeId = $request->get('office_id')) {
            $newOfficeCompany = Office::with('company')->find($officeId);

            if ($newOfficeCompany->company->id !== $employee->office->company->id) {
                return response()->json([
                    'office_id' => ['Selected office is not in same company with current office'],
                ], 422);
            }

            $employee->update([
                'office_id' => $officeId,
            ]);
        }

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

    public function delete($id)
    {
        try {
            $employee = Employee::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Cannot delete non existing employee',
            ], 404);
        }

        if ($employee->is_admin) {
            return response()->json([
                'message' => 'Cannot delete admin of company',
            ], 400);
        }

        $employee->delete();

        return response()->json([]);
    }

    /** @todo Add Test */
    public function employeeCount()
    {
        $this->validate(request(), [
            'company_id' => 'required',
        ]);

        $company = Company::find(request('company_id'));

        return response()->json([
            'count' => $company->activeEmployees()->count(),
        ]);
    }
}
