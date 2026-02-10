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

    @livewire('store-products', ['store' => $store])
</div>
@endsection
