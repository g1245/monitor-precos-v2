<?php

namespace App\Http\Controllers;

use App\Models\Highlight;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $highlights = Highlight::latest()->limit(10)->get();

        $tab = match ($request->route()->getName()) {
            'welcome.recentes'      => 'recentes',
            'welcome.mais-acessados' => 'mais-acessados',
            default                 => 'destaques',
        };

        return view('welcome.index', compact('highlights', 'tab'));
    }
}
