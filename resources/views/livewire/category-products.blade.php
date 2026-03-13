<div class="category-products">
    <div class="container mx-auto px-4 py-8">
        <!-- Linha 1: Título da categoria -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <h1 class="text-3xl font-bold text-gray-800">{{ ucfirst(str_replace('-', ' ', $category)) }}</h1>
                <div class="ml-4 text-gray-600">{{ $products->total() }} produtos</div>
            </div>
        </div>
        
        <!-- Linha 2: Filtros e paginação -->
        <div class="flex flex-wrap justify-between items-center mb-8 bg-white p-4 rounded-lg shadow-sm">
            <!-- Filtros à esquerda -->
            <div class="flex flex-wrap items-center gap-2">
                <div class="mb-2 md:mb-0">
                    <select wire:model.live="perPage" class="bg-white border border-gray-300 rounded-md text-gray-700 h-10 pl-5 pr-10 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="30">30 por página</option>
                        <option value="60">60 por página</option>
                        <option value="120">120 por página</option>
                        <option value="240">240 por página</option>
                    </select>
                </div>
                
                <div class="mb-2 md:mb-0">
                    <select wire:model.live="sortField" class="bg-white border border-gray-300 rounded-md text-gray-700 h-10 pl-5 pr-10 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="name">Ordenar por: Nome</option>
                        <option value="price">Ordenar por: Preço</option>
                        <option value="discount_percentage">Ordenar por: Desconto</option>
                    </select>
                </div>
                
                <div class="mb-2 md:mb-0">
                    <select wire:model.live="sortDirection" class="bg-white border border-gray-300 rounded-md text-gray-700 h-10 pl-5 pr-10 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="asc">Crescente</option>
                        <option value="desc">Decrescente</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Filtros avançados -->
        <div id="filters" class="mb-6 bg-white p-4 rounded-lg shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Filtros</h3>
                @if($minPrice || $maxPrice || $brand || $storeId)
                    <button wire:click="clearFilters" class="text-sm text-primary hover:underline">
                        Limpar filtros
                    </button>
                @endif
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Filtro de preço -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preço</label>
                    <div class="flex items-center gap-2">
                        <input type="number" wire:model.live.debounce.500ms="minPrice" placeholder="Mín." 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <span class="text-gray-500">-</span>
                        <input type="number" wire:model.live.debounce.500ms="maxPrice" placeholder="Máx." 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <!-- Filtro de marca -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                    <input type="text" wire:model.live.debounce.500ms="brand" placeholder="Digite a marca" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <!-- Filtro de loja -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loja (ID)</label>
                    <input type="number" wire:model.live.debounce.500ms="storeId" placeholder="ID da loja" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <p class="text-xs text-gray-500 mt-1">Informe o ID da loja para filtrar</p>
                </div>
            </div>
        </div>

        <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
            <div wire:key="category-product-{{ $product->id }}" class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow flex flex-col">
                {{-- Imagem --}}
                <a href="{{ route('product.show', ['slug' => $product->permalink, 'id' => $product->id]) }}" class="block p-4" x-data="{ loaded: false }" x-init="loaded = $refs.img.complete && $refs.img.naturalWidth > 0">
                    <div class="aspect-square relative overflow-hidden rounded-lg bg-gray-50">
                        <div x-show="!loaded" class="absolute inset-0 bg-gray-200 animate-pulse rounded"></div>
                        <img src="{{ $product->image_url ?? 'https://placehold.co/800x800' }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-contain transition-opacity duration-300"
                             :class="loaded ? 'opacity-100' : 'opacity-0'"
                             x-ref="img"
                             x-on:load="loaded = true"
                             x-on:error="loaded = true">
                    </div>
                </a>

                {{-- Informações --}}
                <div class="px-4 pb-3 flex-1 flex flex-col">
                    <a href="{{ route('product.show', ['slug' => $product->permalink, 'id' => $product->id]) }}" class="block mb-2">
                        <h2 class="text-sm font-medium text-gray-800 line-clamp-2 hover:text-primary transition-colors">
                            {{ $product->name }}
                        </h2>
                    </a>

                    @if($product->price_regular && $product->price_regular > $product->price)
                        <div class="text-xs text-gray-500 line-through">de R$ {{ number_format($product->price_regular, 2, ',', '.') }}</div>
                        <div class="text-xl font-bold text-primary">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                        @php $discount = round((($product->price_regular - $product->price) / $product->price_regular) * 100); @endphp
                        @if($discount > 1)
                            <div class="mt-2">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded">{{ $discount }}% OFF</span>
                            </div>
                        @endif
                    @else
                        <div class="text-xl font-bold text-primary">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                    @endif
                </div>

                {{-- Footer: Loja --}}
                @if($product->store)
                    <div class="px-4 py-2 border-t border-gray-100 flex items-center gap-1.5">
                        @if($product->store->logo)
                            <img src="{{ Storage::disk('public')->url($product->store->logo) }}"
                                 alt="{{ $product->store->name }}"
                                 class="w-4 h-4 object-contain">
                        @endif
                        <span class="text-xs text-gray-500 truncate">{{ $product->store->name }}</span>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <!-- Paginação -->
        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>
    </div>
</div>
