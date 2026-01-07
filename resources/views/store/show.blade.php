@extends('layouts.app')
@section('title', $store->name . ' - Monitor de Preços')
@section('description', 'Veja todos os produtos disponíveis na ' . $store->name . '. Compare preços e encontre as melhores ofertas.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="mb-6 overflow-x-auto">
        <ol class="flex items-center space-x-2 text-sm text-gray-600 whitespace-nowrap">
            <li>
                <a href="/" class="hover:text-primary transition-colors">Home</a>
            </li>
            <li class="flex items-center">
                <svg class="w-4 h-4 mx-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900">{{ $store->name }}</span>
            </li>
        </ol>
    </nav>

    <!-- Store Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center space-x-6">
            @if($store->logo)
                <img src="{{ Storage::disk('public')->url($store->logo) }}" 
                     alt="{{ $store->name }}" 
                     class="w-24 h-24 object-contain rounded-lg border border-gray-200">
            @else
                <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            @endif
            
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $store->name }}</h1>
                
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    @if($store->region)
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $store->region }}
                        </span>
                    @endif
                    
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        {{ $store->products_count }} {{ Str::plural('produto', $store->products_count) }}
                    </span>
                </div>
                
                <div class="mt-4">
                    <a href="{{ $store->full_url }}" 
                       target="_blank" 
                       rel="nofollow noopener"
                       class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Visitar Loja
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
            Produtos Disponíveis
        </h2>
        
        @if($store->products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($store->products as $product)
                    <a href="{{ route('product.show', ['alias' => $product->permalink, 'productId' => $product->id]) }}" 
                       class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-4 group">
                        @if($product->image)
                            <div class="aspect-square mb-3 relative overflow-hidden rounded-lg bg-gray-50">
                                <img src="{{ $product->image }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-200"
                                     loading="lazy">
                            </div>
                        @else
                            <div class="aspect-square mb-3 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <h3 class="text-sm font-medium text-gray-900 line-clamp-2 mb-2 min-h-[2.5rem]">
                            {{ $product->name }}
                        </h3>
                        
                        @if($product->pivot->price)
                            <div class="text-lg font-bold text-primary">
                                R$ {{ number_format($product->pivot->price, 2, ',', '.') }}
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-600 text-lg">Nenhum produto disponível no momento.</p>
            </div>
        @endif
    </div>
</div>
@endsection
