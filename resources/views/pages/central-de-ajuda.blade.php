@extends('layouts.app')

@section('title', 'Central de Ajuda - Monitor de Preços')
@section('description', 'Encontre orientações e respostas para usar o Monitor de Preços.')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Central de Ajuda</h1>
                <p class="mt-2 text-gray-600">Guias rápidos para encontrar produtos e comparar ofertas.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900">Tópicos</h2>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-lg border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900">Busca</h3>
                        <p class="mt-2 text-sm text-gray-700">Use termos simples e refine pela página de resultados.</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900">Comparação</h3>
                        <p class="mt-2 text-sm text-gray-700">Compare a oferta por loja e valide frete/prazo no destino.</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900">Conta</h3>
                        <p class="mt-2 text-sm text-gray-700">Salve itens e acompanhe produtos que você quer monitorar.</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900">Problemas</h3>
                        <p class="mt-2 text-sm text-gray-700">Se algo estiver errado, envie links e detalhes pelo Suporte.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
