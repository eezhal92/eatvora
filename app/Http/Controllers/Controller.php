<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function decoratePaginatedResponse(LengthAwarePaginator $result)
    {
        $result = $result->toArray();
        $query = array_merge([
            'page' => $result['current_page'],
            'lastPage' => $result['last_page'],
            'perPage' => $result['per_page'],
        ], request()->all());

        return [
            'query' => $query,
            'entries' => $result['data'],
            'total' => $result['total'],
        ];
    }
}
