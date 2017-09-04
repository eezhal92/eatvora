<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\LandingPageFormEmail;
use Illuminate\Support\Facades\Mail;

class LandingFormController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'company' => 'required|min:4',
        ]);

        $message = $request->get('message', 'Yang bersangkutan tidak meninggalkan pesan');

        Mail::to(env('EMAIL_SATRIA'))->cc([env('EMAIL_IJAL')])->send(
            new LandingPageFormEmail(request('name'), request('email'), request('company'), $message)
        );

        session()->flash('notif-type', 'success');
        return redirect('/')->with('notif', 'Terima kasih, pesan Anda telah Kami terima. Kami akan segera menghubungi Anda.');
    }
}
