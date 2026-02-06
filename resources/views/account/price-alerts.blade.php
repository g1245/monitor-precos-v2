@extends('layouts.app')

@section('title', 'Alertas de Preço - Monitor de Preços')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('account.dashboard') }}" class="text-blue-600 hover:text-blue-500">Minha Conta</a></li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-600">Alertas de Preço</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Alertas de Preço</h1>
            <p class="mt-2 text-gray-600">Receba notificações quando o preço dos produtos mudar</p>
        </div>

        @if($priceAlerts->isEmpty())
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Nenhum alerta configurado</h3>
                <p class="mt-2 text-gray-500">Configure alertas para receber notificações de mudanças de preço!</p>
                <div class="mt-6">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Explorar Produtos
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach($priceAlerts as $alert)
                    @php
                        $product = $alert->product;
                    @endphp
                    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <!-- Product Image -->
                                <div class="flex-shrink-0">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-24 h-24 object-cover rounded">
                                    @else
                                        <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Product Info -->
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('product.show', ['id' => $product->id, 'slug' => $product->permalink]) }}" class="block">
                                        <h3 class="text-lg font-medium text-gray-900 hover:text-blue-600">{{ $product->name }}</h3>
                                    </a>
                                    
                                    @if($product->store)
                                        <p class="text-sm text-gray-500 mt-1">{{ $product->store->name }}</p>
                                    @endif
                                    
                                    <div class="mt-3 flex items-center space-x-6">
                                        <div>
                                            <p class="text-sm text-gray-500">Preço atual</p>
                                            <p class="text-xl font-bold text-primary">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                                        </div>
                                        
                                        @if($alert->target_price)
                                            <div>
                                                <p class="text-sm text-gray-500">Alertar quando menor que</p>
                                                <p class="text-xl font-bold text-yellow-600">R$ {{ number_format($alert->target_price, 2, ',', '.') }}</p>
                                            </div>
                                        @else
                                            <div>
                                                <p class="text-sm text-gray-500">Tipo de alerta</p>
                                                <p class="text-lg font-medium text-blue-600">Qualquer mudança de preço</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    @if($alert->last_notified_at)
                                        <p class="text-xs text-gray-500 mt-2">Última notificação: {{ $alert->last_notified_at->diffForHumans() }}</p>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex-shrink-0 flex flex-col space-y-2">
                                    <button onclick="removeAlert({{ $product->id }})" 
                                            class="px-4 py-2 bg-red-50 text-red-600 text-sm rounded hover:bg-red-100 transition-colors">
                                        Remover Alerta
                                    </button>
                                    <a href="{{ route('product.show', ['id' => $product->id, 'slug' => $product->permalink]) }}" 
                                       class="px-4 py-2 bg-blue-50 text-blue-600 text-sm rounded hover:bg-blue-100 transition-colors text-center">
                                        Ver Produto
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($priceAlerts->hasPages())
                <div class="mt-8">
                    {{ $priceAlerts->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<script>
function removeAlert(productId) {
    if (!confirm('Tem certeza que deseja remover este alerta?')) {
        return;
    }

    fetch(`/api/price-alerts/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
