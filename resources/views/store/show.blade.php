@extends('layouts.app')
@section('title', $store->name . ' - Monitor de Preços')
@section('description', 'Veja todos os produtos disponíveis na ' . $store->name . '. Compare preços e encontre as melhores ofertas.')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="mb-6 overflow-x-auto">
        <ol class="flex items-center space-x-2 text-sm text-gray-600 whitespace-nowrap">
            <li>
                <a href="/" class="hover:text-primary transition-colors">Inicial</a>
            </li>
            <li class="flex items-center">
                <svg class="w-4 h-4 mx-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>

                <a href="{{ route('stores.index') }}" class="hover:text-primary transition-colors">Lojas</a>
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

                @if($store->metadata && isset($store->metadata['description']))
                    <div class="text-gray-700 mb-2">{{ $store->metadata['description'] }}</div>
                @endif
                
                @if($store->metadata && isset($store->metadata['website']))
                    <div class="text-sm text-gray-500">
                        <a href="{{ $store->metadata['website'] }}" 
                           target="_blank" 
                           rel="nofollow noopener"
                           class="text-primary hover:underline">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                {{ parse_url($store->metadata['website'], PHP_URL_HOST) }}
                            </span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Top 20 maiores descontos</h2>
        <h3 class="mb-4">Os maiores descontos disponíveis na loja {{ $store->name }} nesse momento.</h3>
        
        <!-- Filtros -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
            <form method="GET" action="{{ route('store.show', ['slug' => $store->name, 'id' => $store->id]) }}" id="filterForm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Filtros</h3>
                    @if(request()->hasAny(['minPrice', 'maxPrice', 'minPriceRegular', 'maxPriceRegular', 'brand', 'storeId']))
                        <a href="{{ route('store.show', ['slug' => $store->name, 'id' => $store->id]) }}" class="text-sm text-primary hover:underline">
                            Limpar filtros
                        </a>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Filtro de preço -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preço</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="minPrice" value="{{ request('minPrice') }}" placeholder="Mín." 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                                   onchange="document.getElementById('filterForm').submit()">
                            <span class="text-gray-500">-</span>
                            <input type="number" name="maxPrice" value="{{ request('maxPrice') }}" placeholder="Máx." 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                                   onchange="document.getElementById('filterForm').submit()">
                        </div>
                    </div>

                    <!-- Filtro de preço regular -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preço Regular</label>
                        <div class="flex items-center gap-2">
                            <input type="number" name="minPriceRegular" value="{{ request('minPriceRegular') }}" placeholder="Mín." 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                                   onchange="document.getElementById('filterForm').submit()">
                            <span class="text-gray-500">-</span>
                            <input type="number" name="maxPriceRegular" value="{{ request('maxPriceRegular') }}" placeholder="Máx." 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                                   onchange="document.getElementById('filterForm').submit()">
                        </div>
                    </div>

                    <!-- Filtro de marca -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                        <input type="text" name="brand" value="{{ request('brand') }}" placeholder="Digite a marca" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                               onchange="document.getElementById('filterForm').submit()">
                    </div>

                    <!-- Filtro de loja -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID da Loja</label>
                        <input type="number" name="storeId" value="{{ request('storeId') }}" placeholder="ID da loja" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                               onchange="document.getElementById('filterForm').submit()">
                    </div>
                </div>
            </form>
        </div>
        
        @if($store->products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($store->products as $product)
                    <a href="{{ route('product.show', ['slug' => $product->permalink, 'id' => $product->id]) }}" 
                       class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-4 group">
                        @if($product->image_url !== null)
                            <div class="aspect-square mb-3 relative overflow-hidden rounded-lg bg-gray-50">
                                <img src="{{ $product->image_url }}" 
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
                        
                        <div class="text-sm font-medium text-gray-900 line-clamp-2 mb-2 min-h-[2.5rem]">
                            {{ $product->name }}
                        </div>

                        @if($product->price_regular && $product->price_regular > $product->price)
                            <div class="">
                                <span class="line-through text-sm text-gray-500">de R$ {{ number_format($product->price_regular, 2, ',', '.') }}</span>

                                <span class="text-xl font-bold text-primary">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                            </div>

                            @php $discount = round((($product->price_regular - $product->price) / $product->price_regular) * 100); @endphp

                            @if($discount > 1)
                                <div class="flex items-center mt-2 mb-2">
                                    <div class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded mr-2">
                                        {{ $discount }}% OFF
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-xl font-bold text-primary">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
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
