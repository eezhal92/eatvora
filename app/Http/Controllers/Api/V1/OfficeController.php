<?php

namespace App\Http\Controllers\Api\V1;

use App\Office;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @todo
 * Add Tests
 */
class OfficeController extends Controller
{
    public function index($companyId)
    {
        $withPaginate = request('no_paginate', 'false') === 'false';

        $query = Office::where('company_id', $companyId);

        if ($withPaginate) {
            $query->paginate(request('limit', 20));
        }

        $offices = $query->get();

        return response()->json($offices);
    }
}
