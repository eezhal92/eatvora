<?php

namespace App\Http\Controllers\Api\V1;

use App\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        if ($vendorId = $request->get('vendor_id')) {
            $query->where('vendor_id', $vendorId);
        }

        if ($q = $request->get('query')) {
            $query->where('name', 'like', "%{$q}%");
        }

        return $query->paginate(20);
    }
}
