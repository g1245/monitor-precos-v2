<div>
    {{-- Tab Navigation --}}
    <div class="border-b border-gray-200 mb-8">
        <nav class="-mb-px flex gap-6" aria-label="Tabs">
            <a href="{{ route('welcome') }}"
               class="{{ $tab === 'destaques' ? 'border-primary text-primary font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap border-b-2 py-3 text-sm transition-colors">
                Maiores Descontos
            </a>
            <a href="{{ route('welcome.recentes') }}"
               class="{{ $tab === 'recentes' ? 'border-primary text-primary font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap border-b-2 py-3 text-sm transition-colors">
                Recentes
            </a>
            <a href="{{ route('welcome.mais-acessados') }}"
               class="{{ $tab === 'mais-acessados' ? 'border-primary text-primary font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap border-b-2 py-3 text-sm transition-colors">
                Mais Acessados
            </a>
        </nav>
    </div>

    {{-- Product Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
        @forelse($products as $product)
            <a href="{{ route('product.show', ['slug' => $product->permalink, 'id' => $product->id]) }}"
               class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-4 group border border-gray-200">
                @if($product->image_url)
                    <div class="aspect-square mb-3 relative overflow-hidden rounded-lg bg-gray-50">
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-200"
                             loading="lazy">
                        @if($product->discount_percentage)
                            <div class="absolute top-2 right-2 bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full">
                                -{{ $product->discount_percentage }}%
                            </div>
                        @endif
                    </div>
                @else
                    <div class="aspect-square mb-3 bg-gray-100 rounded-lg flex items-center justify-center relative">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        @if($product->discount_percentage)
                            <div class="absolute top-2 right-2 bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full">
                                -{{ $product->discount_percentage }}%
                            </div>
                        @endif
                    </div>
                @endif

                <h3 class="text-sm font-medium text-gray-900 line-clamp-2 mb-2 min-h-[2.5rem]">
                    {{ $product->name }}
                </h3>

                <div class="space-y-1">
                    @if($product->price_regular && $product->price_regular > $product->price)
                        <div class="text-xs text-gray-500 line-through">
                            R$ {{ number_format($product->price_regular, 2, ',', '.') }}
                        </div>
                    @endif
                    @if($product->price)
                        <div class="text-lg font-bold text-primary">
                            R$ {{ number_format($product->price, 2, ',', '.') }}
                        </div>
                    @endif
                </div>

                @if($product->store)
                    <div class="mt-2 pt-2 border-t border-gray-100">
                        <div class="flex items-center text-xs text-gray-600">
                            @if($product->store->logo)
                                <img src="{{ Storage::disk('public')->url($product->store->logo) }}"
                                     alt="{{ $product->store->name }}"
                                     class="w-4 h-4 object-contain mr-1">
                            @endif
                            <span class="truncate">{{ $product->store->name }}</span>
                        </div>
                    </div>
                @endif
            </a>
        @empty
            <p class="col-span-4 text-center text-gray-500 py-12">Nenhum produto encontrado.</p>
        @endforelse
    </div>

    {{-- Infinite Scroll Sentinel --}}
    @if($hasMore)
        <div
            wire:key="sentinel-{{ $limit }}"
            x-data="{}"
            x-init="
                let observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting) {
                        $wire.loadMore();
                    }
                }, { rootMargin: '300px' });
                observer.observe($el);
            "
            class="h-4 mt-6">
        </div>
    @endif

    {{-- Loading Spinner --}}
    <div wire:loading class="flex justify-center py-8">
        <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-label="Carregando">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    </div>
</div>
