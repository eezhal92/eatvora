<?php

namespace App\Http\Controllers\Admin;

use App\CompanyPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function index()
    {
        $query = CompanyPayment::with('company')->orderBy('created_at', 'desc');

        if (request('date_from') && request('date_to')) {
            $query->whereBetween('created_at', [request('date_from'), request('date_to')]);
        }

        if ($companyId = request('company_id')) {
            $query->where('company_id', $companyId);
        }

        $payments = $query->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }
}
