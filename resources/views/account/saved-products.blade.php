@extends('layouts.app')

@section('title', 'Produtos Salvos - Monitor de Preços')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('account.dashboard') }}" class="text-blue-600 hover:text-blue-500">Minha Conta</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-600">Produtos Salvos</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Produtos Salvos</h1>
            <p class="mt-2 text-gray-600">Seus produtos favoritos em um só lugar</p>
        </div>

        @if($savedProducts->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Nenhum produto salvo</h3>
                <p class="mt-2 text-gray-500">Comece a salvar produtos para acompanhar preços!</p>
                <div class="mt-6">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Explorar Produtos
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($savedProducts as $savedProduct)
                    @php
                        $product = $savedProduct->product;
                    @endphp
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden">
                        <a href="{{ route('product.show', ['id' => $product->id, 'slug' => $product->permalink]) }}" class="block">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-4">
                            <a href="{{ route('product.show', ['id' => $product->id, 'slug' => $product->permalink]) }}" class="block">
                                <h3 class="text-sm font-medium text-gray-900 line-clamp-2 mb-2">{{ $product->name }}</h3>
                            </a>
                            
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-2xl font-bold text-primary">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                    @if($product->price_regular && $product->price_regular > $product->price)
                                        <p class="text-xs text-gray-500 line-through">R$ {{ number_format($product->price_regular, 2, ',', '.') }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            @if($product->store)
                                <p class="text-xs text-gray-500 mb-3">{{ $product->store->name }}</p>
                            @endif
                            
                            <div class="flex space-x-2">
                                <button onclick="removeSavedProduct({{ $product->id }})" 
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
            @if($savedProducts->hasPages())
                <div class="mt-8">
                    {{ $savedProducts->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<script>
function removeSavedProduct(productId) {
    if (!confirm('Tem certeza que deseja remover este produto?')) {
        return;
    }

    fetch(`/api/saved-products/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.saved === false) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
