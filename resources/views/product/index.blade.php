@extends('layouts.app')
@section('title', $product->name . ' - Monitor de Preços')
@section('description', 'Compare preços do ' . $product->name . ' em diversas lojas. Encontre a melhor oferta e economize!')

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
                <a href="#" class="hover:text-primary transition-colors">Games</a>
            </li>
            <li class="flex items-center">
                <svg class="w-4 h-4 mx-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900">Console de Vídeo Game</span>
            </li>
        </ol>
    </nav>

    <!-- Header Actions -->
    <div class="flex justify-end mb-4 space-x-3">
        <button class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:text-primary transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span>Salvar</span>
        </button>
        <button class="flex items-center space-x-2 px-4 py-2 text-gray-700 hover:text-primary transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
            </svg>
            <span>Compartilhar</span>
        </button>
    </div>

    <!-- Main Content Grid -->
    <div class="grid lg:grid-cols-4 gap-8">
        <!-- Left Content (3 columns) -->
        <div class="lg:col-span-3">
            <!-- Product Header -->
            <div class="mb-8">
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="flex">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @endfor
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
                        <img src="{{ $product->image_url ?? 'https://via.placeholder.com/400x400/f3f4f6/9ca3af?text=' . urlencode($product->name) }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-contain product-image-main">
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    <div class="flex justify-center space-x-2">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="w-16 h-16 bg-gray-100 rounded border border-gray-200 p-2 cursor-pointer hover:border-primary transition-colors product-thumbnail {{ $i === 0 ? 'active' : '' }}">
                                <img src="https://via.placeholder.com/64x64/f3f4f6/9ca3af?text={{ $i + 1 }}" 
                                     alt="Thumbnail {{ $i + 1 }}"
                                     class="w-full h-full object-contain">
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Price and Actions -->
                <div class="space-y-6">
                    <!-- Price Section -->
                    <div class="space-y-2">
                        <div class="text-3xl lg:text-4xl font-bold price-current">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </div>
                        @if($product->regular_price && $product->regular_price > $product->price)
                            <div class="text-lg price-original">
                                De R$ {{ number_format($product->regular_price, 2, ',', '.') }}
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button class="w-full action-button-primary text-white font-semibold py-3 px-6 rounded-lg transition-colors">
                            Comparar em {{ count($storeOffers) }} lojas
                        </button>
                        
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                            <button class="action-button-secondary flex items-center justify-center space-x-2 text-gray-700 py-2 px-4 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                <span class="hidden sm:inline">Compartilhar</span>
                            </button>
                            
                            <button class="action-button-secondary flex items-center justify-center space-x-2 text-gray-700 py-2 px-4 rounded-lg transition-colors" data-action="copy-link">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <span class="hidden sm:inline">Copiar link</span>
                            </button>
                            
                            <button class="action-button-secondary flex items-center justify-center p-2 text-gray-700 rounded-lg hover:border-red-500 hover:text-red-500 transition-colors lg:col-span-1 col-span-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="ml-2 lg:hidden">Adicionar aos favoritos</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Section -->
            <div class="space-y-6">
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8">
                        <button class="py-3 px-1 border-b-2 border-primary text-primary font-medium" data-tab="prices">
                            Preços
                        </button>
                        <button class="py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium" data-tab="history">
                            Histórico
                        </button>
                        <button class="py-3 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium" data-tab="specs">
                            Ficha técnica
                        </button>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div id="prices-tab" class="tab-content">
                    <!-- Store Comparison Section -->
                    <div class="space-y-4">
                        <!-- Section Header -->
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900">Compare preços em 9 lojas</h2>
                            <div class="flex items-center space-x-3">
                                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                                    <option>Ordenar por: Padrão</option>
                                    <option>Menor preço</option>
                                    <option>Maior preço</option>
                                </select>
                                <button class="p-2 border border-gray-300 rounded-lg hover:border-primary transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Store Cards -->
                        <div class="space-y-3">
                            @foreach($storeOffers as $offer)
                                <div class="store-offer-card border border-gray-200 rounded-lg p-4 hover:border-primary transition-colors">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                        <!-- Left Section -->
                                        <div class="flex items-start lg:items-center space-x-4">
                                            @if($offer['is_best_price'])
                                                <div class="badge-best-price text-white text-xs font-medium px-2 py-1 rounded shrink-0">
                                                    Menor preço
                                                </div>
                                            @endif
                                            
                                            <div class="w-12 h-12 shrink-0">
                                                <img src="{{ $offer['store_logo'] }}" alt="{{ $offer['store_name'] }}" class="w-full h-full object-contain rounded">
                                            </div>
                                            
                                            <div class="space-y-1 min-w-0 flex-1">
                                                <div class="text-xl lg:text-2xl font-bold text-gray-900">
                                                    R$ {{ number_format($offer['price'], 2, ',', '.') }}
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $offer['installment_price'] }}
                                                </div>
                                                
                                                <div class="flex flex-wrap items-center gap-2">
                                                    @if($offer['discount_percentage'])
                                                        <span class="badge-discount text-purple-800 text-xs font-medium px-2 py-1 rounded">
                                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                            </svg>
                                                            {{ $offer['discount_percentage'] }}% na loja toda • R$ {{ $offer['cashback'] ?? '0,00' }}
                                                        </span>
                                                    @endif
                                                    
                                                    @if($offer['coupon'])
                                                        <span class="badge-coupon text-blue-800 text-xs font-medium px-2 py-1 rounded">
                                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a.997.997 0 01-1.414 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                            </svg>
                                                            Cupom: {{ $offer['coupon'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Section -->
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 lg:min-w-max">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 rounded-full overflow-hidden shrink-0">
                                                    <img src="{{ $offer['store_logo'] }}" alt="{{ $offer['store_name'] }}" class="w-full h-full object-cover">
                                                </div>
                                                <span class="font-medium text-gray-900 text-sm">{{ $offer['store_name'] }}</span>
                                                <div class="flex items-center">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <svg class="w-3 h-3 {{ $i <= floor($offer['store_rating']) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                            
                                            <a href="{{ $offer['link'] }}" 
                                               target="_blank"
                                               class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-lg transition-colors text-center w-full sm:w-auto">
                                                Ir à loja
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Load More Button -->
                        <div class="text-center pt-4">
                            <button class="text-primary hover:text-primary-dark font-medium">
                                Ver mais ofertas
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Price History Tab -->
                <div id="history-tab" class="tab-content hidden">
                    <div class="space-y-6">
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
                                        O produto está no menor preço dos últimos 6 meses
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="text-xs text-gray-500">6 meses</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Time Period Filter -->
                        <div class="flex items-center justify-center space-x-2 flex-wrap gap-2">
                            <button class="period-filter-btn px-4 py-2 text-sm border border-gray-300 rounded-lg hover:border-primary transition-colors">40 dias</button>
                            <button class="period-filter-btn px-4 py-2 text-sm border border-gray-300 rounded-lg hover:border-primary transition-colors">3 meses</button>
                            <button class="period-filter-btn px-4 py-2 text-sm bg-primary text-white rounded-lg">6 meses</button>
                            <button class="period-filter-btn px-4 py-2 text-sm border border-gray-300 rounded-lg hover:border-primary transition-colors">1 ano</button>
                        </div>
                    </div>
                </div>

                <!-- Technical Specifications Tab -->
                <div id="specs-tab" class="tab-content hidden">
                    <div class="space-y-6">
                        <h2 class="text-xl font-semibold text-gray-900">Ficha técnica</h2>
                        
                        <div class="grid lg:grid-cols-2 gap-8">
                            <!-- Console Specifications -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Características do Console</h3>
                                
                                <div class="space-y-3">
                                    @foreach($technicalSpecs['console_specs'] as $key => $value)
                                        @if($key !== 'Recursos e Entretenimentos')
                                            <div class="flex flex-col sm:flex-row py-3 border-b border-gray-100">
                                                <div class="sm:w-1/2 text-sm text-gray-600 font-medium mb-1 sm:mb-0">{{ $key }}</div>
                                                <div class="sm:w-1/2 text-sm text-gray-900">{{ $value }}</div>
                                            </div>
                                        @else
                                            <div class="flex flex-col py-3 border-b border-gray-100">
                                                <div class="text-sm text-gray-600 font-medium mb-2">{{ $key }}</div>
                                                <div class="text-sm space-y-1">
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($value as $feature)
                                                            <span class="inline-block text-primary hover:text-primary-dark cursor-pointer bg-blue-50 px-2 py-1 rounded text-xs">
                                                                {{ $feature }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Description -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Descrição</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        {{ $technicalSpecs['description'] }}
                                    </p>
                                </div>
                                
                                <!-- Additional Info -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="text-sm">
                                            <div class="font-medium text-blue-900 mb-1">Informação importante</div>
                                            <div class="text-blue-800">Este é um produto bundle que inclui jogos adicionales. Verifique a disponibilidade dos títulos na sua região.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- View Complete Specs Button -->
                        <div class="text-center pt-4">
                            <button class="text-primary hover:text-primary-dark font-medium underline underline-offset-2 hover:no-underline transition-all">
                                Ver ficha técnica completa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (1 column) -->
        <div class="lg:col-span-1 space-y-6 order-first lg:order-last">
            <!-- Historical Low Price Card -->
            <div class="sidebar-card bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-teal-500 rounded-full shrink-0"></div>
                    <span class="text-sm font-medium text-gray-900">Menor preço histórico</span>
                </div>
                <p class="text-sm text-gray-600">
                    O produto está no menor preço dos últimos 6 meses
                </p>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-orange-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2 12l10-10v6h10v8h-10v6z"/>
                        </svg>
                        <div class="text-xs text-gray-500">6 meses</div>
                    </div>
                    <button class="text-primary hover:text-primary-dark text-sm font-medium" onclick="showHistoryTab()">
                        Ver Histórico
                    </button>
                </div>
            </div>

            <!-- Want to Pay Less Card -->
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
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">Ativar alertas</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" value="" class="sr-only peer" id="price-alert-toggle">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                </div>
            </div>

            <!-- Quick Actions (Mobile Only) -->
            <div class="lg:hidden bg-white border border-gray-200 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Ações rápidas</h3>
                <div class="flex space-x-2">
                    <button class="flex-1 action-button-secondary flex items-center justify-center space-x-2 text-gray-700 py-2 px-4 rounded-lg transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        <span>Compartilhar</span>
                    </button>
                    <button class="action-button-secondary p-2 text-gray-700 rounded-lg hover:border-red-500 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('[data-tab]');
    const tabContents = document.querySelectorAll('.tab-content');

    function switchTab(targetTab) {
        // Remove active classes
        tabButtons.forEach(btn => {
            btn.classList.remove('border-primary', 'text-primary');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        // Hide all tab contents
        tabContents.forEach(content => {
            content.classList.add('hidden');
        });

        // Find and activate the correct button
        const activeButton = document.querySelector(`[data-tab="${targetTab}"]`);
        if (activeButton) {
            activeButton.classList.remove('border-transparent', 'text-gray-500');
            activeButton.classList.add('border-primary', 'text-primary');
        }

        // Show target tab content
        const targetContent = document.getElementById(targetTab + '-tab');
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            switchTab(targetTab);
        });
    });

    // Global function for sidebar link
    window.showHistoryTab = function() {
        switchTab('history');
    };

    // Price history chart
    const ctx = document.getElementById('priceChart');
    if (ctx) {
        const priceHistory = @json($priceHistory['data']);
        
        new Chart(ctx, {
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
});
</script>
@endpush