@extends('layouts.app')
@section('title', $product->name . ' - Monitor de Preços')
@section('description', 'Compare preços do ' . $product->name . ' em diversas lojas. Encontre a melhor oferta e economize!')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <nav class="mb-6 overflow-x-auto">
            <ol class="flex items-center space-x-2 text-sm text-gray-600 whitespace-nowrap">
                <li>
                    <a href="/" class="hover:text-primary transition-colors">Home</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-900">{{ ucwords($product->name) }}</span>
                </li>
            </ol>
        </nav>



        <div class="grid lg:grid-cols-4 gap-8">
            <div class="lg:col-span-3">
                <div class="mb-8">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ ucwords($product->name) }}</h1>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-500">sem avaliações</span>
                    </div>
                </div>

                <!-- Product Details Grid -->
                <div class="grid lg:grid-cols-2 gap-8 mb-8">
                    <!-- Image Gallery -->
                    <div class="space-y-4">
                        <!-- Main Image -->
                        <div class="aspect-square bg-white rounded-lg border border-gray-200 p-6">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain product-image-main">
                        </div>
                    </div>

                    <!-- Price and Actions -->
                    <div class="space-y-6">
                        <!-- Price Section -->
                        <div class="space-y-2">
                            @if($product->price_regular && $product->price_regular > $product->price)
                                <div class="text-lg price-original">
                                    De R$ {{ number_format($product->price_regular, 2, ',', '.') }}
                                </div>
                            @endif
                            <div class="text-3xl lg:text-4xl font-bold price-current">
                                R$ {{ number_format($product->price, 2, ',', '.') }}
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <a target="_blank-{{ $product->id }}" href="{{ $product->deep_link }}" class="block w-full text-center action-button-primary text-white font-semibold py-3 px-6 rounded-lg transition-colors" title="Comprar {{ $product->name }} na loja {{ $product->store->name }}">
                                Comprar
                            </a>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <button class="action-button-secondary flex items-center justify-center space-x-2 text-gray-700 py-2 px-4 rounded-lg transition-colors" data-action="copy-link">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Copiar link</span>
                                </button>
                                
                                <a href="{{ route('product.share.whatsapp', $product->id) }}" target="_blank" class="action-button-secondary flex items-center justify-center space-x-2 text-gray-700 py-2 px-4 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                    </svg>
                                    <span class="hidden sm:inline">Compartilhar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6 order-first lg:order-last">
                <!-- Save Product Card -->
                @auth
                    <div class="sidebar-card bg-white border border-gray-200 rounded-lg p-4">
                        <button id="saveProductBtn" data-product-id="{{ $product->id }}" class="w-full flex items-center justify-between save-btn transition-colors">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5 save-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                <span class="text-sm font-medium save-text">Salvar produto</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="sidebar-card bg-white border border-gray-200 rounded-lg p-4">
                        <a href="{{ route('auth.login') }}" class="w-full flex items-center justify-between text-gray-700 hover:text-primary transition-colors">
                            <div class="flex items-center space-x-3">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                <span class="text-sm font-medium">Salvar produto</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                @endauth
                
                <div class="sidebar-card bg-white border border-gray-200 rounded-lg p-4 space-y-3 cursor-pointer hover:border-primary hover:shadow-md transition-all" id="price-history-card">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-teal-500 rounded-full shrink-0"></div>
                        <span class="text-sm font-medium text-gray-900">Veja o histórico de preço</span>
                    </div>
                    <p class="text-sm text-gray-600">
                        Acesso ao gráfico com alterações de preço
                    </p>
                    <div class="flex items-center justify-between">
                        <button class="text-primary hover:text-primary-dark text-sm font-medium">
                            Ver Histórico
                        </button>
                    </div>
                </div>

                <!-- Want to Pay Less Card -->
                @auth
                    <div class="sidebar-card bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Quer pagar mais barato?</span>
                        </div>
                        <p class="text-sm text-gray-600">
                            Avisamos quando o preço baixar
                        </p>
                        <div class="grid grid-cols-2 gap-2">
                            <button id="saveProductBtnCard" data-product-id="{{ $product->id }}" class="flex items-center justify-center space-x-1 border border-gray-300 text-gray-700 text-sm font-medium py-2 px-3 rounded-lg hover:border-primary hover:text-primary transition-colors save-btn-card">
                                <svg class="w-4 h-4 save-icon-card" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                </svg>
                                <span class="save-text-card">Salvar</span>
                            </button>
                            <button class="flex items-center justify-center space-x-1 bg-purple-600 text-white text-sm font-medium py-2 px-3 rounded-lg hover:bg-purple-700 transition-colors price-alert-trigger">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span>Alerta</span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="sidebar-card bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-purple-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-900">Quer pagar mais barato?</span>
                        </div>
                        <p class="text-sm text-gray-600">
                            Avisamos quando o preço baixar
                        </p>
                        <a href="{{ route('auth.login') }}" class="block w-full text-center bg-purple-600 text-white text-sm font-medium py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors">
                            Fazer login para criar alerta
                        </a>
                    </div>
                @endauth

                <!-- Quick Actions (Mobile Only) -->
                <div class="lg:hidden bg-white border border-gray-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Ações rápidas</h3>
                    <div class="flex space-x-2">
                        <button class="flex-1 action-button-secondary flex items-center justify-center space-x-2 text-gray-700 py-2 px-4 rounded-lg transition-colors text-sm" data-action="copy-link">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span>Copiar link</span>
                        </button>
                        <a href="{{ route('product.share.whatsapp', $product->id) }}" target="_blank" class="flex-1 action-button-secondary flex items-center justify-center space-x-2 text-gray-700 py-2 px-4 rounded-lg transition-colors text-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                            </svg>
                            <span>Compartilhar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        @if($priceHistory['has_history'])
            <div id="price-history" class="space-y-6 mb-12">
                <h2 class="text-xl font-semibold text-gray-900">Histórico de Preços</h2>
                
                <!-- Chart Container -->
                <div class="relative bg-white border border-gray-200 rounded-lg p-6">
                    <div class="h-80">
                        <canvas id="priceChart"></canvas>
                    </div>
                    
                    <!-- Info Card Overlay -->
                    <div class="absolute top-4 right-4 bg-white border border-gray-200 rounded-lg p-4 shadow-lg max-w-xs">
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-teal-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-900">Menor preço histórico</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                Veja o preço histórico
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="text-xs text-gray-500">{{ count($priceHistory['data']) }} registros</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Mensagem quando não há histórico -->
            <div class="space-y-6 mb-12">
                <h2 class="text-xl font-semibold text-gray-900">Histórico de Preços</h2>
                
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
                    <div class="max-w-sm mx-auto">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2h2a2 2 0 002-2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Ainda não há histórico de preços</h3>
                        <p class="text-gray-600">O gráfico com o histórico de preços aparecerá quando tivermos dados suficientes para este produto.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Ficha Técnica Section -->
        <div class="space-y-6 mb-12">
            <h2 class="text-xl font-semibold text-gray-900">Ficha técnica</h2>
            
            @if($product->attributes->count() > 0)
                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- Product Specifications -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Características do Produto</h3>
                        
                        <div class="space-y-3">
                            @foreach($product->attributes as $attribute)
                                <div class="flex flex-col sm:flex-row py-3 border-b border-gray-100">
                                    <div class="sm:w-1/2 text-sm text-gray-600 font-medium mb-1 sm:mb-0">{{ $attribute->key }}</div>
                                    <div class="sm:w-1/2 text-sm text-gray-900">{{ $attribute->description }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">Descrição</h3>
                        @if($product->description)
                            <div class="bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ $product->description }}
                                </p>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500 leading-relaxed italic">
                                    Descrição não disponível para este produto.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- No attributes available -->
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <div class="text-gray-400 mb-2">
                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Ficha técnica em breve</h3>
                    <p class="text-gray-600">As especificações técnicas deste produto serão disponibilizadas em breve.</p>
                </div>
            @endif
        </div>

        <!-- Price Alert Modal -->
        @auth
        <div id="priceAlertModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Configurar Alerta de Preço</h3>
                        <button class="text-gray-400 hover:text-gray-600" id="closeModal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 mb-4" id="modalDescription">
                            Digite o preço que você deseja pagar por este produto. Vamos notificá-lo quando o preço atingir ou ficar abaixo desse valor.
                        </p>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Preço Atual: <span class="text-primary font-semibold">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                            </label>
                        </div>
                        
                        <div class="mb-4">
                            <label for="targetPrice" class="block text-sm font-medium text-gray-700 mb-2">
                                Preço Desejado (R$)
                            </label>
                            <input 
                                type="number" 
                                id="targetPrice" 
                                step="0.01" 
                                min="0"
                                placeholder="Ex: {{ number_format($product->price * 0.9, 2, ',', '.') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            >
                            <p class="mt-1 text-xs text-gray-500">Deixe em branco para ser notificado sobre qualquer mudança de preço</p>
                        </div>
                        
                        <div id="existingAlertActions" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <p class="text-sm text-yellow-800 mb-2">
                                Você já tem um alerta configurado para este produto.
                            </p>
                            <p class="text-sm font-medium text-yellow-900">
                                Preço alvo atual: <span id="currentTargetPrice"></span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3 mt-5">
                        <button 
                            id="removeAlertBtn" 
                            class="hidden flex-1 bg-red-100 text-red-700 px-4 py-2 rounded-md hover:bg-red-200 transition-colors"
                        >
                            Remover Alerta
                        </button>
                        <button 
                            id="saveAlertBtn" 
                            class="flex-1 bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition-colors"
                        >
                            Salvar Alerta
                        </button>
                        <button 
                            id="cancelBtn" 
                            class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition-colors"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endauth
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global chart instance variable
            let priceChartInstance = null;

            // Price history chart
            const ctx = document.getElementById('priceChart');

            if (ctx) {
                const priceHistory = @json($priceHistory['data']);
                const hasHistory = @json($priceHistory['has_history']);
                
                if (hasHistory && priceHistory.length > 0) {
                    // Destroy existing chart instance if it exists
                    if (priceChartInstance) {
                        priceChartInstance.destroy();
                    }

                    priceChartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: priceHistory.map(item => item.formatted_date),
                            datasets: [{
                                label: 'Preço',
                                data: priceHistory.map(item => item.price),
                                borderColor: '#06b6d4',
                                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.3,
                                pointBackgroundColor: '#06b6d4',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            elements: {
                                point: {
                                    hoverBackgroundColor: '#0891b2'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: false,
                                    grid: {
                                        color: '#f3f4f6'
                                    },
                                    ticks: {
                                        callback: function(value) {
                                            return 'R$ ' + value.toLocaleString('pt-BR', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                        },
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: {
                                            size: 11
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#ffffff',
                                    bodyColor: '#ffffff',
                                    borderColor: '#06b6d4',
                                    borderWidth: 1,
                                    callbacks: {
                                        label: function(context) {
                                            return 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Copy link functionality
            const copyLinkBtn = document.querySelector('[data-action="copy-link"]');
            
            if (copyLinkBtn) {
                copyLinkBtn.addEventListener('click', function() {
                    navigator.clipboard.writeText(window.location.href).then(() => {
                        // Simple feedback - you could replace with a toast notification
                        const originalText = this.innerHTML;
                        this.innerHTML = this.innerHTML.replace('Copiar link', 'Copiado!');
                        this.classList.add('text-green-600', 'border-green-500');
                        
                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.classList.remove('text-green-600', 'border-green-500');
                        }, 2000);
                    }).catch(() => {
                        console.log('Erro ao copiar link');
                    });
                });
            }

            // Image gallery functionality
            const thumbnails = document.querySelectorAll('.product-thumbnail');
            const mainImage = document.querySelector('.product-image-main');
            
            thumbnails.forEach((thumbnail, index) => {
                thumbnail.addEventListener('click', function() {
                    // Remove active class from all thumbnails
                    thumbnails.forEach(thumb => thumb.classList.remove('active'));
                    
                    // Add active class to clicked thumbnail
                    this.classList.add('active');
                    
                    // Update main image (in a real app, you'd have actual different images)
                    if (mainImage) {
                        mainImage.style.opacity = '0.5';
                        setTimeout(() => {
                            // Here you would update the src with the actual image
                            mainImage.style.opacity = '1';
                        }, 150);
                    }
                });
            });

            // Store offer cards hover effects
            const storeCards = document.querySelectorAll('.store-offer-card');
            
            storeCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '';
                });
            });

            // Price alert toggle functionality
            const priceAlertToggle = document.getElementById('price-alert-toggle');
            
            if (priceAlertToggle) {
                priceAlertToggle.addEventListener('change', function() {
                    if (this.checked) {
                        // Here you would implement the actual alert subscription
                        console.log('Alert de preço ativado');
                    } else {
                        console.log('Alert de preço desativado');
                    }
                });
            }

            // Price history card click functionality
            const priceHistoryCard = document.getElementById('price-history-card');
            
            if (priceHistoryCard) {
                priceHistoryCard.addEventListener('click', function() {
                    const priceHistorySection = document.getElementById('price-history');
                    
                    if (priceHistorySection) {
                        priceHistorySection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            }

            // Time period filter for price history
            const periodButtons = document.querySelectorAll('.period-filter-btn');
            periodButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active state from all buttons
                    periodButtons.forEach(btn => {
                        btn.classList.remove('bg-primary', 'text-white');
                        btn.classList.add('border', 'border-gray-300');
                    });
                    
                    // Add active state to clicked button
                    this.classList.remove('border', 'border-gray-300');
                    this.classList.add('bg-primary', 'text-white');
                    
                    // In a real app, you would update the chart data here
                    console.log('Período selecionado:', this.textContent);
                });
            });

            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                if (priceChartInstance) {
                    priceChartInstance.destroy();
                    priceChartInstance = null;
                }
            });
        });

        // Product Save and Alert functionality
        @auth
        document.addEventListener('DOMContentLoaded', function() {
            const saveBtn = document.getElementById('saveProductBtn');
            const productId = {{ $product->id }};
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            // Modal elements
            const modal = document.getElementById('priceAlertModal');
            const closeModalBtn = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const saveAlertBtn = document.getElementById('saveAlertBtn');
            const removeAlertBtn = document.getElementById('removeAlertBtn');
            const targetPriceInput = document.getElementById('targetPrice');
            const existingAlertActions = document.getElementById('existingAlertActions');
            const currentTargetPriceSpan = document.getElementById('currentTargetPrice');
            
            // Get save button in the card
            const saveProductBtnCard = document.getElementById('saveProductBtnCard');
            
            // Get all price alert triggers (only the button, not the card)
            const priceAlertTriggers = document.querySelectorAll('.price-alert-trigger');
            
            let currentAlertData = null;

            // Check initial state
            checkWishStatus();

            // Save button handler (sidebar)
            if (saveBtn) {
                saveBtn.addEventListener('click', function() {
                    toggleSaveProduct();
                });
            }

            // Save button handler (card)
            if (saveProductBtnCard) {
                saveProductBtnCard.addEventListener('click', function() {
                    toggleSaveProductCard();
                });
            }

            // Price alert triggers - open modal
            priceAlertTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    openPriceAlertModal();
                });
            });

            // Modal close handlers
            [closeModalBtn, cancelBtn].forEach(btn => {
                if (btn) {
                    btn.addEventListener('click', () => closeModal());
                }
            });

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Save alert button handler
            saveAlertBtn.addEventListener('click', function() {
                saveOrUpdateAlert();
            });

            // Remove alert button handler
            removeAlertBtn.addEventListener('click', function() {
                removeAlert();
            });

            function openPriceAlertModal() {
                // Fetch current wish/alert status first
                fetch(`/wish-products/${productId}/check`, {
                    headers: {
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    currentAlertData = data;
                    
                    if (data.has_alert && data.target_price) {
                        // Show existing alert info
                        existingAlertActions.classList.remove('hidden');
                        removeAlertBtn.classList.remove('hidden');
                        currentTargetPriceSpan.textContent = `R$ ${parseFloat(data.target_price).toFixed(2).replace('.', ',')}`;
                        targetPriceInput.value = data.target_price;
                    } else {
                        // New alert
                        existingAlertActions.classList.add('hidden');
                        removeAlertBtn.classList.add('hidden');
                        targetPriceInput.value = '';
                    }
                    
                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Erro ao buscar informações do alerta', 'error');
                });
            }

            function closeModal() {
                modal.classList.add('hidden');
                targetPriceInput.value = '';
            }

            function saveOrUpdateAlert() {
                const targetPrice = targetPriceInput.value.trim();
                
                const body = {
                    product_id: productId,
                };
                
                if (targetPrice && !isNaN(parseFloat(targetPrice))) {
                    body.target_price = parseFloat(targetPrice);
                }

                // If wish doesn't exist yet, create it with alert
                const url = currentAlertData && currentAlertData.wished 
                    ? `/wish-products/${productId}/price-alert`
                    : '/wish-products';
                    
                const method = currentAlertData && currentAlertData.wished 
                    ? 'PATCH'
                    : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(body)
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(data.message || 'Alerta configurado com sucesso!');
                    closeModal();
                    checkWishStatus();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Erro ao salvar alerta', 'error');
                });
            }

            function removeAlert() {
                if (!confirm('Tem certeza que deseja remover este alerta de preço?')) {
                    return;
                }

                fetch(`/wish-products/${productId}/price-alert`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ target_price: null })
                })
                .then(response => response.json())
                .then(data => {
                    showNotification(data.message || 'Alerta removido com sucesso!');
                    closeModal();
                    checkWishStatus();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Erro ao remover alerta', 'error');
                });
            }

            function checkWishStatus() {
                fetch(`/wish-products/${productId}/check`, {
                    headers: {
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateSaveButton(data.wished);
                    updateSaveButtonCard(data.wished);
                    updatePriceAlertCard(data.has_alert);
                })
                .catch(error => console.error('Error:', error));
            }

            function toggleSaveProduct() {
                const isSaved = saveBtn.classList.contains('saved');
                const url = isSaved ? `/wish-products/${productId}` : '/wish-products';
                const method = isSaved ? 'DELETE' : 'POST';

                const options = {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                };

                if (!isSaved) {
                    options.body = JSON.stringify({ product_id: productId });
                }

                fetch(url, options)
                    .then(response => response.json())
                    .then(data => {
                        updateSaveButton(data.wished);
                        updateSaveButtonCard(data.wished);
                        showNotification(data.message);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Erro ao processar solicitação', 'error');
                    });
            }

            function toggleSaveProductCard() {
                const isSaved = saveProductBtnCard.classList.contains('saved');
                const url = isSaved ? `/wish-products/${productId}` : '/wish-products';
                const method = isSaved ? 'DELETE' : 'POST';

                const options = {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                };

                if (!isSaved) {
                    options.body = JSON.stringify({ product_id: productId });
                }

                fetch(url, options)
                    .then(response => response.json())
                    .then(data => {
                        updateSaveButton(data.wished);
                        updateSaveButtonCard(data.wished);
                        showNotification(data.message);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Erro ao processar solicitação', 'error');
                    });
            }

            function updateSaveButton(isSaved) {
                const icon = saveBtn.querySelector('.save-icon');
                const text = saveBtn.querySelector('.save-text');
                
                if (isSaved) {
                    saveBtn.classList.add('saved', 'bg-blue-100', 'text-blue-600');
                    saveBtn.classList.remove('text-gray-700', 'hover:text-primary');
                    icon.setAttribute('fill', 'currentColor');
                    text.textContent = 'Produto salvo';
                } else {
                    saveBtn.classList.remove('saved', 'bg-blue-100', 'text-blue-600');
                    saveBtn.classList.add('text-gray-700', 'hover:text-primary');
                    icon.setAttribute('fill', 'none');
                    text.textContent = 'Salvar produto';
                }
            }

            function updateSaveButtonCard(isSaved) {
                if (!saveProductBtnCard) return;
                
                const icon = saveProductBtnCard.querySelector('.save-icon-card');
                const text = saveProductBtnCard.querySelector('.save-text-card');
                
                if (isSaved) {
                    saveProductBtnCard.classList.add('saved', 'bg-blue-100', 'text-blue-600', 'border-blue-300');
                    saveProductBtnCard.classList.remove('text-gray-700', 'hover:text-primary', 'border-gray-300');
                    icon.setAttribute('fill', 'currentColor');
                    text.textContent = 'Salvo';
                } else {
                    saveProductBtnCard.classList.remove('saved', 'bg-blue-100', 'text-blue-600', 'border-blue-300');
                    saveProductBtnCard.classList.add('text-gray-700', 'hover:text-primary', 'border-gray-300');
                    icon.setAttribute('fill', 'none');
                    text.textContent = 'Salvar';
                }
            }

            function updatePriceAlertCard(hasAlert) {
                // Update alert button text and color based on alert status
                const alertButtons = document.querySelectorAll('.price-alert-trigger');
                
                alertButtons.forEach(button => {
                    const span = button.querySelector('span');
                    if (span && span.textContent === 'Alerta') {
                        // This is the card button with just "Alerta" text
                        if (hasAlert) {
                            button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                            button.classList.add('bg-green-600', 'hover:bg-green-700');
                        } else {
                            button.classList.remove('bg-green-600', 'hover:bg-green-700');
                            button.classList.add('bg-purple-600', 'hover:bg-purple-700');
                        }
                    }
                });
            }

            function showNotification(message, type = 'success') {
                const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-300`;
                notification.textContent = message;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }
        });
        @endauth
    </script>
@endpush