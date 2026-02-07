<div class="department-products">
    <div class="container mx-auto px-4 py-8">
        <!-- Linha 1: Título do departamento -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <h1 class="text-3xl font-bold text-gray-800">{{ $department->name }}</h1>
                <div class="ml-4 text-gray-600">{{ $products->total() }} produtos</div>
            </div>
        </div>
        
        <!-- Linha 2: Filtros e paginação -->
        <div class="flex flex-wrap justify-between items-center mb-8 bg-white p-4 rounded-lg shadow-sm">
            <!-- Filtros à esquerda -->
            <div class="flex flex-wrap items-center">
                <div class="mr-2 mb-2 md:mb-0">
                    <select wire:model.live="perPage" class="bg-white border border-gray-300 rounded-md text-gray-700 h-10 pl-5 pr-10 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="12">12 por página</option>
                        <option value="24">24 por página</option>
                        <option value="36">36 por página</option>
                        <option value="48">48 por página</option>
                    </select>
                </div>
                
                <div class="mr-2 mb-2 md:mb-0">
                    <select wire:model.live="sortField" class="bg-white border border-gray-300 rounded-md text-gray-700 h-10 pl-5 pr-10 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="name">Ordenar por: Nome</option>
                        <option value="price">Ordenar por: Preço</option>
                        <option value="discount_percentage">Ordenar por: Desconto</option>
                    </select>
                </div>
                
                <div>
                    <select wire:model.live="sortDirection" class="bg-white border border-gray-300 rounded-md text-gray-700 h-10 pl-5 pr-10 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="asc">Crescente</option>
                        <option value="desc">Decrescente</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden transition-transform hover:shadow-lg">
                <!-- Marca no topo -->
                <div class="p-3 text-sm font-medium text-gray-700 border-b border-gray-100">{{ $product->brand }}</div>
                
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
    </div>
</div>