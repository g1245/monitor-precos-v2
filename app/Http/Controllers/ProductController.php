<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(string $productId, Request $request)
    {
        return view('product.index', ['permalink' => $productId]);
    }
}
