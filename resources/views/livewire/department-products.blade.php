<div class="department-products">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-wrap justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ $department->name }}</h1>
            <div class="text-gray-600">{{ $products->total() }} produtos</div>
            
            <div class="w-full md:w-auto mt-4 md:mt-0">
                <select wire:model.live="perPage" class="bg-white border border-gray-300 rounded-md text-gray-700 h-10 pl-5 pr-10 hover:border-primary focus:outline-none">
                    <option value="12">12 por página</option>
                    <option value="24">24 por página</option>
                    <option value="36">36 por página</option>
                    <option value="48">48 por página</option>
                </select>
                
                <select wire:model.live="sortField" class="bg-white border border-gray-300 rounded-md text-gray-700 h-10 pl-5 pr-10 hover:border-primary focus:outline-none ml-2">
                    <option value="name">Ordenar por: Nome</option>
                    <option value="price">Ordenar por: Preço</option>
                    <option value="brand">Ordenar por: Marca</option>
                    <option value="created_at">Ordenar por: Mais recentes</option>
                </select>
                
                <select wire:model.live="sortDirection" class="bg-white border border-gray-300 rounded-md text-gray-700 h-10 pl-5 pr-10 hover:border-primary focus:outline-none ml-2">
                    <option value="asc">Crescente</option>
                    <option value="desc">Decrescente</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden transition-transform hover:shadow-lg">
                <div class="p-2 text-xs text-gray-600">{{ $product->brand }}</div>
                <a href="{{ route('product.show', ['alias' => $product->permalink, 'productId' => $product->id]) }}" class="block">
                    <img src="{{ $product->image_url ?? 'https://placehold.co/800' }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-120 object-contain mx-auto">
                </a>
                
                <div class="p-4">
                    <div class="flex flex-col h-full">
                        <a href="{{ route('product.show', ['alias' => $product->permalink, 'productId' => $product->id]) }}" class="block mb-2">
                            <h2 class="text-sm font-medium text-gray-800 line-clamp-2 hover:text-primary transition-colors">
                                {{ $product->name }}
                            </h2>
                        </a>
                        
                        @if($product->regular_price && $product->regular_price > $product->price)
                            <div class="line-through text-sm text-gray-500">
                                R$ {{ number_format($product->regular_price, 2, ',', '.') }}
                            </div>
                            <div class="flex items-center">
                                <div class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded mr-2">
                                    {{ round((($product->regular_price - $product->price) / $product->regular_price) * 100) }}% OFF
                                </div>
                            </div>
                        @endif
                        
                        <div class="text-xl font-bold text-primary mt-1">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </div>
                        
                        <div class="text-xs text-gray-500 mt-1">
                            {{ floor($product->price / 10) }}x de R$ {{ number_format($product->price / floor($product->price / 10), 2, ',', '.') }} sem juros
                        </div>
                        
                        <div class="text-xs flex items-center text-green-600 mt-2">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path>
                            </svg>
                            Até R$ {{ number_format($product->price * 0.1, 2, ',', '.') }} de cashback
                        </div>
                        
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-xs text-gray-700">
                                Sai por:<br/>
                                <span class="font-bold">R$ {{ number_format($product->price - ($product->price * 0.1), 2, ',', '.') }}</span>
                            </div>
                            
                            <button class="bg-primary hover:bg-primary-dark text-white rounded-full w-10 h-10 flex items-center justify-center transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
</div>