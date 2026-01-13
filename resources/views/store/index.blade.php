@extends('layouts.app')

@section('title', 'Lojas - Monitor de Preços')
@section('description', 'Explore todas as lojas parceiras do Monitor de Preços e encontre os melhores produtos.')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Lojas Parceiras</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($stores as $store)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    @if($store->logo)
                        <img src="{{ $store->logo }}" alt="{{ $store->name }}" class="w-12 h-12 rounded-full mr-4 object-cover">
                    @else
                        <div class="w-12 h-12 bg-gray-200 rounded-full mr-4 flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ $store->name }}</h2>
                    </div>
                </div>
                <a href="{{ route('store.show', ['id' => $store->id, 'slug' => $store->getSlug()]) }}" class="inline-block w-full text-center bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors">
                    Ver Produtos
                </a>
            </div>
        </div>
        @endforeach
    </div>

    @if($stores->isEmpty())
    <div class="text-center py-12">
        <p class="text-gray-500 text-lg">Nenhuma loja encontrada.</p>
    </div>
    @endif
</div>
@endsection