<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PagesController extends Controller
{
    /**
     * @var array<string, string>
     */
    private const PAGE_VIEWS = [
        'sobre-nos' => 'pages.sobre-nos',
        'como-funciona' => 'pages.como-funciona',
        'lojas-parceiras' => 'pages.lojas-parceiras',
        'contato' => 'pages.contato',
        'central-de-ajuda' => 'pages.central-de-ajuda',
        'politica-de-privacidade' => 'pages.politica-de-privacidade',
        'termos-de-uso' => 'pages.termos-de-uso',
        'faq' => 'pages.faq',
        'suporte' => 'pages.suporte',
    ];

    /**
     * @var array<string, string>
     */
    private const CATEGORY_VIEWS = [
        'eletronicos' => 'pages.categories.eletronicos',
        'celulares' => 'pages.categories.celulares',
        'informatica' => 'pages.categories.informatica',
        'eletrodomesticos' => 'pages.categories.eletrodomesticos',
        'casa-e-decoracao' => 'pages.categories.casa-e-decoracao',
    ];

    /**
     * Display a static informational page.
     */
    public function show(string $slug): View
    {
        $view = self::PAGE_VIEWS[$slug] ?? null;

        if ($view === null) {
            abort(404);
        }

        return view($view);
    }

    /**
     * Display a static category landing page.
     */
    public function category(string $slug): View
    {
        $view = self::CATEGORY_VIEWS[$slug] ?? null;

        if ($view === null) {
            abort(404);
        }

        return view($view);
    }
}
