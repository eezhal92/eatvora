<?php

namespace App\Http\Controllers\Api\V1;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            ->select('users.*')
            ->where('offices.id', $officeId);

        if ($q = $request->get('query')) {
            $query->where('users.name', 'like', "%{$q}%");
        }

        $totalEmployees = $query->count();

        $employees = $query
            ->limit($limit)
            ->skip($offset)
            ->get();

        $pageCount = ceil($totalEmployees / $limit);

        return response()->json([
            'current_page' => (int) $page,
            'page_count' => $pageCount,
            'employees' => $employees,
        ]);
    }
}
