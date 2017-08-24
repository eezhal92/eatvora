<?php

namespace App\Http\Controllers\Api\V1;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        // @todo add test
        return Category::all();
    }
}
