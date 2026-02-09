@extends('layouts.app')

@section('title', 'Lojas Parceiras - Monitor de Preços')
@section('description', 'Entenda como as lojas aparecem no Monitor de Preços e acesse a lista de lojas.')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Lojas Parceiras</h1>
                <p class="mt-2 text-gray-600">Ofertas reunidas para facilitar sua comparação.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900">Como funciona a listagem</h2>
                <p class="mt-4 text-gray-700 leading-relaxed">
                    Exibimos produtos e preços de lojas virtuais para que você compare as opções em um só lugar.
                    As condições finais de compra (frete, prazo e disponibilidade) são definidas pela loja.
                </p>

                <div class="mt-8 rounded-lg bg-blue-50 border border-blue-100 p-5">
                    <h3 class="font-semibold text-blue-900">Quer ver todas as lojas?</h3>
                    <p class="mt-2 text-sm text-blue-900/80">Acesse a lista completa de lojas disponíveis na plataforma.</p>
                    <a href="{{ route('stores.index') }}" class="mt-4 inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Ver lojas
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
