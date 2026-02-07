<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $highlights = Highlight::latest()->get();

        return view('welcome.index', compact('highlights'));
    }
}
