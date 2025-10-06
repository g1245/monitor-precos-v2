<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartamentController extends Controller
{
    public function index(string $permalink, Request $request)
    {
        return view('departament.index', ['permalink' => $permalink]);
    }
}
