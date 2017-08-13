<?php

namespace App\Http\Controllers\Api\V1;

use App\CompanyPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function update(Request $request, $id)
    {
        $payment = CompanyPayment::find($id);

        $payment->update([
            'note' => $request->get('note'),
        ]);

        return response()->json($payment);
    }
}
