@extends('layouts.app')

@section('title', 'Minha Conta - Monitor de Preços')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Minha Conta</h1>
        <p class="mt-2 text-gray-600">Olá, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button onclick="showTab('saved')" id="tab-saved" class="tab-button active border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Produtos Salvos
            </button>
            <button onclick="showTab('alerts')" id="tab-alerts" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Alertas de Preço
            </button>
            <button onclick="showTab('history')" id="tab-history" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Histórico de Visitas
            </button>
        </nav>
    </div>

    <!-- Saved Products Tab -->
    <div id="content-saved" class="tab-content">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Produtos Salvos</h2>
            <p class="mt-1 text-sm text-gray-600">Acompanhe seus produtos favoritos</p>
        </div>

        @if($savedProducts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($savedProducts as $saved)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="aspect-square bg-gray-100 p-4">
                            <img src="{{ $saved->product->image_url ?? 'https://via.placeholder.com/300' }}" 
                                 alt="{{ $saved->product->name }}" 
                                 class="w-full h-full object-contain">
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-medium text-gray-900 line-clamp-2 mb-2">
                                {{ $saved->product->name }}
                            </h3>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-lg font-bold text-primary">
                                    R$ {{ number_format($saved->product->price, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('product.show', ['alias' => $saved->product->permalink, 'productId' => $saved->product->id]) }}" 
                                   class="flex-1 bg-primary hover:bg-primary-dark text-white text-sm font-medium py-2 px-4 rounded-lg text-center transition-colors">
                                    Ver produto
                                </a>
                                <button onclick="removeSaved({{ $saved->product->id }})"
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $savedProducts->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum produto salvo</h3>
                <p class="mt-1 text-sm text-gray-500">Comece salvando produtos que você gosta!</p>
                <div class="mt-6">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark">
                        Explorar produtos
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Price Alerts Tab -->
    <div id="content-alerts" class="tab-content hidden">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Alertas de Preço</h2>
            <p class="mt-1 text-sm text-gray-600">Receba notificações quando o preço baixar</p>
        </div>

        @if($priceAlerts->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($priceAlerts as $alert)
                        <li>
                            <div class="px-4 py-4 flex items-center sm:px-6">
                                <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <img class="h-16 w-16 object-contain" src="{{ $alert->product->image_url ?? 'https://via.placeholder.com/100' }}" alt="{{ $alert->product->name }}">
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $alert->product->name }}
                                            </p>
                                            <p class="mt-1 flex items-center text-sm text-gray-500">
                                                Preço atual: 
                                                <span class="ml-1 font-semibold text-primary">
                                                    R$ {{ number_format($alert->product->price, 2, ',', '.') }}
                                                </span>
                                            </p>
                                            <p class="mt-1 text-xs text-gray-400">
                                                Alerta criado em {{ $alert->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex-shrink-0 sm:mt-0 sm:ml-5">
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Ativo
                                            </span>
                                            <a href="{{ route('product.show', ['alias' => $alert->product->permalink, 'productId' => $alert->product->id]) }}" 
                                               class="text-primary hover:text-primary-dark">
                                                Ver produto
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum alerta configurado</h3>
                <p class="mt-1 text-sm text-gray-500">Configure alertas para ser notificado quando os preços baixarem</p>
            </div>
        @endif
    </div>

    <!-- Visit History Tab -->
    <div id="content-history" class="tab-content hidden">
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Histórico de Visitas</h2>
            <p class="mt-1 text-sm text-gray-600">Seus produtos e departamentos visitados recentemente</p>
        </div>

        @if($recentVisits->count() > 0)
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @foreach($recentVisits as $visit)
                        <li>
                            <a href="{{ $visit->url }}" class="block hover:bg-gray-50">
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                @if($visit->visitable_type === 'App\Models\Product')
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                    </svg>
                                                @else
                                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $visit->visitable->name ?? 'Item não disponível' }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $visit->visitable_type === 'App\Models\Product' ? 'Produto' : 'Departamento' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="ml-2 flex-shrink-0">
                                            <p class="text-xs text-gray-500">
                                                {{ $visit->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhuma visita registrada</h3>
                <p class="mt-1 text-sm text-gray-500">Comece navegando pelos produtos e departamentos</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function showTab(tab) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('border-primary', 'text-primary');
            button.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show selected tab
        document.getElementById('content-' + tab).classList.remove('hidden');
        
        // Add active class to selected button
        const activeButton = document.getElementById('tab-' + tab);
        activeButton.classList.remove('border-transparent', 'text-gray-500');
        activeButton.classList.add('border-primary', 'text-primary');
    }

    function removeSaved(productId) {
        if (!confirm('Deseja remover este produto dos salvos?')) {
            return;
        }

        fetch('{{ route("account.toggle-save") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ product_id: productId })
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
@endpush
@endsection
