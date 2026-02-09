@extends('layouts.app')

@section('title', 'Sobre Nós - Monitor de Preços')
@section('description', 'Conheça o Monitor de Preços e como ajudamos você a economizar comparando ofertas.')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Sobre Nós</h1>
                <p class="mt-2 text-gray-600">Comparação simples, ofertas claras e mais economia.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900">O que é o Monitor de Preços?</h2>
                    <p class="mt-4 text-gray-700 leading-relaxed">
                        O Monitor de Preços é um catálogo de produtos que reúne ofertas de diversas lojas virtuais do Brasil.
                        Nosso objetivo é facilitar sua decisão: comparar opções rapidamente, entender variações e encontrar boas oportunidades.
                    </p>

                    <h2 class="mt-8 text-xl font-semibold text-gray-900">Como ajudamos você</h2>
                    <ul class="mt-4 space-y-3 text-gray-700">
                        <li class="flex gap-3">
                            <span class="mt-1 h-2 w-2 rounded-full bg-blue-600"></span>
                            <span>Comparação de preços por loja para o mesmo produto.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 h-2 w-2 rounded-full bg-blue-600"></span>
                            <span>Histórico e acompanhamento das variações para você decidir com mais segurança.</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 h-2 w-2 rounded-full bg-blue-600"></span>
                            <span>Recursos de conta para salvar itens e acompanhar suas preferências.</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900">Atalhos</h2>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('search.index') }}" class="block rounded-md border border-gray-200 px-4 py-3 text-sm text-gray-700 hover:border-blue-300 hover:bg-blue-50">
                            Buscar produtos
                        </a>
                        <a href="{{ route('stores.index') }}" class="block rounded-md border border-gray-200 px-4 py-3 text-sm text-gray-700 hover:border-blue-300 hover:bg-blue-50">
                            Ver lojas
                        </a>
                        <a href="{{ route('pages.how') }}" class="block rounded-md border border-gray-200 px-4 py-3 text-sm text-gray-700 hover:border-blue-300 hover:bg-blue-50">
                            Entender como funciona
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
