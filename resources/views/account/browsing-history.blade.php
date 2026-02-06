@extends('layouts.app')

@section('title', 'Histórico de Navegação - Monitor de Preços')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('account.dashboard') }}" class="text-blue-600 hover:text-blue-500">Minha Conta</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-600">Histórico de Navegação</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Histórico de Navegação</h1>
            <p class="mt-2 text-gray-600">Veja os produtos e páginas que você visitou recentemente</p>
        </div>

        @if($history->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Nenhum histórico encontrado</h3>
                <p class="mt-2 text-gray-500">Comece a navegar para construir seu histórico!</p>
                <div class="mt-6">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Explorar Produtos
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @php
                    $currentDate = null;
                @endphp
                @foreach($history as $item)
                    @php
                        $itemDate = $item->visited_at->format('Y-m-d');
                        $showDateHeader = $currentDate !== $itemDate;
                        $currentDate = $itemDate;
                    @endphp
                    
                    @if($showDateHeader)
                        <div class="pt-4 pb-2">
                            <h2 class="text-lg font-semibold text-gray-700">
                                @if($item->visited_at->isToday())
                                    Hoje
                                @elseif($item->visited_at->isYesterday())
                                    Ontem
                                @else
                                    {{ $item->visited_at->format('d/m/Y') }}
                                @endif
                            </h2>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg shadow hover:shadow-md transition-shadow p-4">
                        <div class="flex items-start space-x-4">
                            <!-- Icon based on page type -->
                            <div class="flex-shrink-0">
                                @if($item->page_type === 'product' && $item->product)
                                    @if($item->product->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-16 h-16 bg-blue-100 rounded flex items-center justify-center">
                                        @if($item->page_type === 'department')
                                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        @elseif($item->page_type === 'store')
                                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        @elseif($item->page_type === 'search')
                                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        @else
                                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                            </svg>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        @if($item->page_type === 'product' && $item->product)
                                            <a href="{{ route('product.show', ['id' => $item->product->id, 'slug' => $item->product->permalink]) }}" class="block">
                                                <h3 class="text-base font-medium text-gray-900 hover:text-blue-600">
                                                    {{ $item->product->name }}
                                                </h3>
                                            </a>
                                            @if($item->product->store)
                                                <p class="text-sm text-gray-500">{{ $item->product->store->name }}</p>
                                            @endif
                                        @elseif($item->page_type === 'department' && $item->department)
                                            <h3 class="text-base font-medium text-gray-900">
                                                Departamento: {{ $item->department->name }}
                                            </h3>
                                        @elseif($item->page_type === 'store' && $item->store)
                                            <h3 class="text-base font-medium text-gray-900">
                                                Loja: {{ $item->store->name }}
                                            </h3>
                                        @elseif($item->page_type === 'search')
                                            <h3 class="text-base font-medium text-gray-900">
                                                Busca realizada
                                            </h3>
                                        @else
                                            <h3 class="text-base font-medium text-gray-900">
                                                Página inicial
                                            </h3>
                                        @endif
                                        
                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $item->visited_at->format('H:i') }}
                                        </p>
                                    </div>

                                    @if($item->page_type === 'product' && $item->product)
                                        <a href="{{ route('product.show', ['id' => $item->product->id, 'slug' => $item->product->permalink]) }}" 
                                           class="ml-4 px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors">
                                            Visitar novamente
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($history->hasPages())
                <div class="mt-8">
                    {{ $history->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
