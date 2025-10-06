<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(string $permalink, Request $request)
    {
        return view('product.index', ['permalink' => $permalink]);
    }
}
