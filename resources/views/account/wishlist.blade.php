@extends('layouts.app')

@section('title', 'Minha Lista de Desejos - Monitor de Preços')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('account.dashboard') }}" class="text-blue-600 hover:text-blue-500">Minha Conta</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-600">Lista de Desejos</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Minha Lista de Desejos</h1>
            <p class="mt-2 text-gray-600">Produtos que você salvou e acompanha</p>
        </div>

        @if($userWishes->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Nenhum produto na lista</h3>
                <p class="mt-2 text-gray-500">Comece a adicionar produtos à sua lista de desejos!</p>
                <div class="mt-6">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Explorar Produtos
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($userWishes as $wish)
                    @php
                        $product = $wish->product;
                    @endphp
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden">
                        <a href="{{ route('product.show', ['id' => $product->id, 'slug' => $product->permalink]) }}" class="block relative">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Alert Badge -->
                            @if($wish->hasPriceAlert())
                                <div class="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-medium flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    Alerta
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-4">
                            <a href="{{ route('product.show', ['id' => $product->id, 'slug' => $product->permalink]) }}" class="block">
                                <h3 class="text-sm font-medium text-gray-900 line-clamp-2 mb-2">{{ $product->name }}</h3>
                            </a>
                            
                            <div class="mb-3">
                                <div class="flex items-baseline justify-between">
                                    <p class="text-2xl font-bold text-primary">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                    @if($product->price_regular && $product->price_regular > $product->price)
                                        <p class="text-xs text-gray-500 line-through">R$ {{ number_format($product->price_regular, 2, ',', '.') }}</p>
                                    @endif
                                </div>
                                
                                @if($wish->hasPriceAlert())
                                    <div class="mt-1 text-xs text-yellow-700">
                                        Alertar quando ≤ R$ {{ number_format($wish->target_price, 2, ',', '.') }}
                                    </div>
                                @endif
                            </div>
                            
                            @if($product->store)
                                <p class="text-xs text-gray-500 mb-3">{{ $product->store->name }}</p>
                            @endif
                            
                            <div class="flex space-x-2">
                                <button onclick="removeFromWishlist({{ $product->id }})" 
                                        class="flex-1 bg-red-50 text-red-600 text-sm px-3 py-2 rounded hover:bg-red-100 transition-colors">
                                    Remover
                                </button>
                                <a href="{{ route('product.show', ['id' => $product->id, 'slug' => $product->permalink]) }}" 
                                   class="flex-1 bg-blue-600 text-white text-sm px-3 py-2 rounded hover:bg-blue-700 transition-colors text-center">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($userWishes->hasPages())
                <div class="mt-8">
                    {{ $userWishes->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<script>
function removeFromWishlist(productId) {
    if (!confirm('Tem certeza que deseja remover este produto da sua lista de desejos?')) {
        return;
    }

    fetch(`/api/wish-products/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.wished === false) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
