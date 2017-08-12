<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Office;
use App\Company;
use App\Employee;
use App\CompanyPayment;
use Illuminate\Http\Request;
use App\Facades\RandomPassword;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\CompanyRegistrationEmail;
use App\Mail\EmployeeRegistrationEmail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::paginate(10);

        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'company_name' => 'required|min:3',
            'company_address' => 'required|min:6',
            'admin_name' => 'required|min:3',
            'admin_email' => 'required|email',
        ]);

        $company = Company::create([
            'name' => $request->get('company_name'),
        ]);

        $office = Office::create([
            'company_id' => $company->id,
            'name' => $request->get('company_name'),
            'address' => $request->get('company_address'),
            'is_main' => true,
        ]);

        $randomTemporaryPassword = RandomPassword::generate();
        $user = User::create([
            'name' => $request->get('admin_name'),
            'email' => $request->get('admin_email'),
            'password' => bcrypt($randomTemporaryPassword),
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'office_id' => $office->id,
            'is_admin' => true,
        ]);

        Mail::to($user->email)->send(new CompanyRegistrationEmail($company));
        Mail::to($user->email)->send(new EmployeeRegistrationEmail($company, $user, $randomTemporaryPassword));

        return redirect("/ap/companies/{$company->id}");
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);
        $offices = $company->offices()->simplePaginate(10);

        return view('admin.companies.show', compact('company', 'offices'));
    }

    public function payments($id)
    {
        $company = Company::findOrFail($id);

        $payments = CompanyPayment::where('company_id', $id)->paginate(20);

        return view('admin.companies.payment', compact('company', 'payments'));
    }

    public function edit(Request $request, $id)
    {
        $company = Company::find($id);
        $mainOffice = $company->mainOffice();

        return view('admin.companies.edit', compact('company', 'mainOffice'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'main_office_name' => 'required|min:3',
            'main_office_address' => 'required|min:6',
        ]);

        $company = Company::find($id);
        $mainOffice = $company->offices()->where('is_main', true)->first();

        $company->update([
            'name' => $request->get('name'),
        ]);

        $mainOffice->update([
            'name' => $request->get('main_office_name'),
            'address' => $request->get('main_office_address'),
            'email' => $request->get('main_office_email'),
            'phone' => $request->get('main_office_phone'),
        ]);

        return redirect("/ap/companies/{$company->id}");
    }

    public function employees(Request $request, $id)
    {
        try {
            $company = Company::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        $query = User::join('employees', 'users.id', '=', 'employees.user_id')
            ->join('offices', 'employees.office_id', '=', 'offices.id')
            ->select(\DB::raw('employees.id as id'), 'users.name', 'users.email', 'employees.active', 'employees.created_at', \DB::raw('offices.name as office_name'))
            ->where('offices.company_id', $id);

        if ($q = $request->get('query')) {
            $query->where(function ($nestedQuery) use ($q) {
                $nestedQuery->where('users.name', 'like', "%{$q}%")
                    ->orWhere('users.email', 'like', "%{$q}%");
            });
        }

        if ($active = $request->get('active')) {
            $bool = $active === 'true' ? 1 : 0;
            $query->where('employees.active', $bool);
        }

        $employees = $query->paginate(20);

        return view('admin.companies.employees', compact('employees', 'company'));
    }
}
