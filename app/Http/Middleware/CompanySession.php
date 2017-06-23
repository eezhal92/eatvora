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
        $user = $request->user();
        $employees = $user->employees()->get();

        if ($employees->count() > 1) {
            return redirect('/choose-company');
        }

        if (!$employees->count()) {
            return redirect('/')
                ->withSession('error', 'Maaf, Anda tidak terdaftar di perusahaan mana pun.');
        }

        session(['company_id' => $employees->first()->company_id]);

        return $next($request);
    }
}
