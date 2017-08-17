<?php

namespace App\Http\Middleware;

use Closure;

class CompanySession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $employees = $request->user()->employees()->get();

        if ($employees->count() > 1) {
            return redirect('/choose-company');
        }

        if (!$employees->count()) {
            return redirect('/')
                ->withSession('error', 'Maaf, Anda belum terdaftar di perusahaan mana pun.');
        }

        $employee = $employees->first()->load('office');

        session(['employee_id' => $employee->id, 'company_id' => $employee->office->company_id]);

        return $next($request);
    }
}
