@extends('layouts.app')

@section('title', 'Como Funciona - Monitor de Preços')
@section('description', 'Veja como comparar preços, salvar produtos e acompanhar ofertas no Monitor de Preços.')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Como Funciona</h1>
                <p class="mt-2 text-gray-600">Um fluxo simples para comparar e economizar.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <ol class="space-y-6">
                    <li class="flex gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-semibold">1</div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Encontre um produto</h2>
                            <p class="mt-2 text-gray-700">Use a busca para localizar o item desejado e abrir a página do produto.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-semibold">2</div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Compare preços por loja</h2>
                            <p class="mt-2 text-gray-700">Veja as opções disponíveis, condições e escolha a melhor oferta.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-semibold">3</div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Acompanhe e salve</h2>
                            <p class="mt-2 text-gray-700">Se estiver logado, salve produtos para acompanhar mudanças e voltar depois com facilidade.</p>
                        </div>
                    </li>
                </ol>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('search.index') }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Ir para busca
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
