<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(string $permalink, Request $request)
    {
        return view('search.index', ['permalink' => $permalink]);
    }
}
