<div class="search-products">
    <div class="container mx-auto px-4 py-8">
        <!-- Linha 1: Título da busca -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                @if($q)
                    <h1 class="text-3xl font-bold text-gray-800">Resultados para "{{ $q }}"</h1>
                    <div class="ml-4 text-gray-600">{{ $products->total() }} produtos</div>
                @else
                    <h1 class="text-3xl font-bold text-gray-800">Busca</h1>
                    <div class="ml-4 text-gray-600">Digite algo para buscar produtos</div>
                @endif
            </div>
        </div>
        
        @if($products->total() > 0)
            <!-- Linha 2: Filtros e paginação -->
            <div class="flex flex-wrap justify-between items-center mb-8 bg-white p-4 rounded-lg shadow-sm">
                <!-- Filtros à esquerda -->
                <div class="flex flex-wrap items-center gap-2">
                    <div class="mb-2 md:mb-0">
                        <select wire:model.live="perPage" class="bg-white border border-gray-300 rounded-md text-gray-700 h-10 pl-5 pr-10 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="12">12 por página</option>
                            <option value="24">24 por página</option>
                            <option value="36">36 por página</option>
                            <option value="48">48 por página</option>
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
            <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden transition-transform hover:shadow-lg">
                    <!-- Marca no topo -->
                    @if($product->brand)
                        <div class="p-3 text-sm font-medium text-gray-700 border-b border-gray-100">{{ $product->brand }}</div>
                    @endif
                    
                    <!-- Imagem do produto -->
                    <a href="{{ route('product.show', ['slug' => $product->permalink, 'id' => $product->id]) }}" class="block p-4">
                        <img src="{{ $product->image_url ?? 'https://placehold.co/800x800' }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-48 object-contain mx-auto">
                    </a>
                    
                    <!-- Detalhes do produto -->
                    <div class="p-4">
                        <div class="flex flex-col h-full">
                            <!-- Nome do produto -->
                            <a href="{{ route('product.show', ['slug' => $product->permalink, 'id' => $product->id]) }}" class="block mb-2">
                                <h2 class="text-sm font-medium text-gray-800 line-clamp-2 hover:text-primary transition-colors">
                                    {{ $product->name }}
                                </h2>
                            </a>
                            
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
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Paginação -->
            <div class="mt-8 flex justify-center">
                {{ $products->links() }}
            </div>
        @elseif($q)
            <!-- Mensagem de nenhum resultado -->
            <div class="bg-white rounded-lg p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Nenhum produto encontrado</h2>
                <p class="text-gray-600">Tente usar palavras-chave diferentes ou mais genéricas.</p>
            </div>
        @endif
    </div>
</div>
