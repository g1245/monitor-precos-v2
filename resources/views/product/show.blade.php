@extends('layouts.app')
@section('title', $product->name . ' - Monitor de Preços')
@section('description', Str::limit($product->description ?? 'Encontre o melhor preço para ' . $product->name, 160))
@section('content')
<div class="bg-white">
    <div class="container mx-auto px-4 py-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('welcome') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Início
                    </a>
                </li>
                @if($department)
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('department.index', ['alias' => $department->permalink, 'departmentId' => $department->id]) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                            {{ $department->name }}
                        </a>
                    </div>
                </li>
                @endif
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ Str::limit($product->name, 50) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Product Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Image Gallery -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <img id="mainImage" 
                         src="{{ $product->images->first()->image_url ?? $product->image_url ?? 'https://placehold.co/800x800' }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-96 object-contain">
                </div>

                <!-- Thumbnail Gallery -->
                @if($product->images->count() > 0)
                <div class="grid grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                    <div class="cursor-pointer border-2 border-gray-200 hover:border-blue-500 rounded-lg overflow-hidden transition-all thumbnail-image" 
                         data-image="{{ $image->image_url }}">
                        <img src="{{ $image->image_url }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-24 object-contain p-2">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Brand -->
                @if($product->brand)
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Marca:</span> {{ $product->brand }}
                </div>
                @endif

                <!-- Product Name -->
                <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

                <!-- SKU -->
                @if($product->sku)
                <div class="text-sm text-gray-500">
                    <span class="font-medium">SKU:</span> {{ $product->sku }}
                </div>
                @endif

                <!-- Price Section -->
                <div class="border-t border-b border-gray-200 py-6">
                    @if($product->regular_price && $product->regular_price > $product->price)
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xl text-gray-500 line-through">
                            R$ {{ number_format($product->regular_price, 2, ',', '.') }}
                        </span>
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ round((($product->regular_price - $product->price) / $product->regular_price) * 100) }}% OFF
                        </span>
                    </div>
                    @endif

                    <div class="text-4xl font-bold text-blue-600 mb-2">
                        R$ {{ number_format($product->price, 2, ',', '.') }}
                    </div>

                    <div class="text-sm text-gray-600 mb-3">
                        ou {{ floor($product->price / 10) }}x de R$ {{ number_format($product->price / floor($product->price / 10), 2, ',', '.') }} sem juros
                    </div>

                    <div class="flex items-center text-green-600 text-sm">
                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"></path>
                        </svg>
                        Até R$ {{ number_format($product->price * 0.1, 2, ',', '.') }} de cashback
                    </div>

                    <div class="mt-2 text-lg font-semibold text-gray-800">
                        Sai por: <span class="text-green-600">R$ {{ number_format($product->price - ($product->price * 0.1), 2, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Price Alert CTA -->
                <div>
                    @livewire('price-alert-modal', ['productId' => $product->id])
                </div>

                <!-- Buy Button -->
                <div class="flex gap-3">
                    <button class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Comprar Agora
                    </button>
                    <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-3 px-6 rounded-lg transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </div>

                <!-- Delivery Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-gray-700">
                            <p class="font-medium mb-1">Informações de Entrega</p>
                            <p>Frete calculado na finalização da compra</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        @if($product->description)
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Descrição do Produto</h2>
            <div class="text-gray-700 leading-relaxed">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
        @endif

        <!-- Technical Specifications -->
        @if($product->specifications->count() > 0)
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Especificações Técnicas</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($product->specifications as $specification)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 w-1/3">
                                {{ $specification->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $specification->value }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Similar Products Carousel -->
        @if($similarProducts->count() > 0)
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Produtos Similares</h2>
            
            <div class="relative">
                <div class="overflow-hidden" id="similarProductsCarousel">
                    <div class="flex transition-transform duration-300 ease-in-out" id="similarProductsSlider">
                        @foreach($similarProducts as $similarProduct)
                        <div class="flex-shrink-0 w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 px-2">
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden transition-shadow hover:shadow-lg h-full">
                                <!-- Brand -->
                                <div class="p-3 text-sm font-medium text-gray-700 border-b border-gray-100">
                                    {{ $similarProduct->brand }}
                                </div>
                                
                                <!-- Image -->
                                <a href="{{ route('product.show', ['alias' => $similarProduct->permalink, 'productId' => $similarProduct->id]) }}" class="block p-4">
                                    <img src="{{ $similarProduct->image_url ?? 'https://placehold.co/300x300' }}" 
                                         alt="{{ $similarProduct->name }}" 
                                         class="w-full h-40 object-contain mx-auto">
                                </a>
                                
                                <!-- Details -->
                                <div class="p-4">
                                    <a href="{{ route('product.show', ['alias' => $similarProduct->permalink, 'productId' => $similarProduct->id]) }}" class="block mb-2">
                                        <h3 class="text-sm font-medium text-gray-800 line-clamp-2 hover:text-blue-600 transition-colors">
                                            {{ $similarProduct->name }}
                                        </h3>
                                    </a>
                                    
                                    @if($similarProduct->regular_price && $similarProduct->regular_price > $similarProduct->price)
                                    <div class="text-xs text-gray-500 line-through mb-1">
                                        R$ {{ number_format($similarProduct->regular_price, 2, ',', '.') }}
                                    </div>
                                    @endif
                                    
                                    <div class="text-lg font-bold text-blue-600">
                                        R$ {{ number_format($similarProduct->price, 2, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Navigation Arrows -->
                @if($similarProducts->count() > 4)
                <button id="prevSimilar" class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white border border-gray-300 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors shadow-md z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button id="nextSimilar" class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4 w-10 h-10 bg-white border border-gray-300 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors shadow-md z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image Gallery functionality
        const mainImage = document.getElementById('mainImage');
        const thumbnails = document.querySelectorAll('.thumbnail-image');

        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const newImageSrc = this.getAttribute('data-image');
                mainImage.src = newImageSrc;

                // Update active state
                thumbnails.forEach(t => t.classList.remove('border-blue-500'));
                thumbnails.forEach(t => t.classList.add('border-gray-200'));
                this.classList.remove('border-gray-200');
                this.classList.add('border-blue-500');
            });
        });

        // Set first thumbnail as active
        if (thumbnails.length > 0) {
            thumbnails[0].classList.remove('border-gray-200');
            thumbnails[0].classList.add('border-blue-500');
        }

        // Similar Products Carousel
        const slider = document.getElementById('similarProductsSlider');
        const prevBtn = document.getElementById('prevSimilar');
        const nextBtn = document.getElementById('nextSimilar');

        if (slider && prevBtn && nextBtn) {
            let currentPosition = 0;
            const itemWidth = slider.querySelector('.flex-shrink-0').offsetWidth;
            const visibleItems = Math.floor(slider.parentElement.offsetWidth / itemWidth);
            const maxPosition = Math.max(0, slider.children.length - visibleItems);

            prevBtn.addEventListener('click', function() {
                if (currentPosition > 0) {
                    currentPosition--;
                    updateCarousel();
                }
            });

            nextBtn.addEventListener('click', function() {
                if (currentPosition < maxPosition) {
                    currentPosition++;
                    updateCarousel();
                }
            });

            function updateCarousel() {
                const translateX = -(currentPosition * itemWidth);
                slider.style.transform = `translateX(${translateX}px)`;

                // Update button states
                prevBtn.style.opacity = currentPosition === 0 ? '0.5' : '1';
                prevBtn.style.cursor = currentPosition === 0 ? 'default' : 'pointer';
                nextBtn.style.opacity = currentPosition === maxPosition ? '0.5' : '1';
                nextBtn.style.cursor = currentPosition === maxPosition ? 'default' : 'pointer';
            }

            // Initial state
            updateCarousel();
        }
    });
</script>
@endpush
