<?php

namespace App\Http\Controllers\Admin;

use App\Office;
use App\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OfficeController extends Controller
{
    public function create($companyId)
    {
        try {
            $company = Company::findOrFail($companyId);
        } catch (ModelNotFoundException $e) {
            return redirect('/ap/companies')->with('message', 'Company was not found.');
        }

        return view('admin.offices.create', compact('company'));
    }

    public function store(Request $request, $companyId)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'address' => 'required|min:6'
        ]);

        try {
            $company = Company::findOrFail($companyId);
        } catch (ModelNotFoundException $e) {
            return redirect("/ap/companies")->with('message', 'Company was not found.');
        }

        $office = Office::create([
            'company_id' => $company->id,
            'name' => $request->get('name'),
            'address' => $request->get('address'),
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
        ]);

        return redirect("/ap/companies/{$company->id}");
    }
}
