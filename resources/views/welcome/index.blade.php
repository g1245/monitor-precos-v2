@extends('layouts.app')
@section('title', 'Monitor de Preços - Compare preços e encontre as melhores ofertas')
@section('description', 'Compare preços de produtos de lojas virtuais de todo o Brasil e encontre as melhores ofertas.')
@section('content')
    @if($banners->isNotEmpty())
    <!-- Hero Banner Carousel -->
    <section class="banner-gradient py-12 relative overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="relative bg-white/10 backdrop-blur-sm rounded-2xl p-8 overflow-hidden min-h-[300px]">
                <!-- Carousel Container -->
                <!-- Each banner contains two images: one for desktop (hidden md:block) and one for mobile (block md:hidden) -->
                <div class="carousel-container relative">
                    <div class="carousel-wrapper overflow-hidden rounded-lg">
                        <div class="carousel-slides flex transition-transform duration-750 ease-in-out">
                            @foreach($banners as $banner)
                            <!-- Banner {{ $loop->iteration }} -->
                            <div class="carousel-slide w-full flex-shrink-0">
                                @if($banner->link)
                                <a href="{{ $banner->link }}" class="block">
                                @else
                                <div class="block">
                                @endif
                                    <!-- Desktop Image -->
                                    @if($banner->desktop_image)
                                    <img src="{{ Storage::disk('public')->url($banner->desktop_image) }}" 
                                         alt="{{ $banner->title }}" 
                                         class="hidden md:block w-full h-auto max-h-[250px] object-contain mx-auto">
                                    @endif
                                    <!-- Mobile Image -->
                                    @if($banner->mobile_image)
                                    <img src="{{ Storage::disk('public')->url($banner->mobile_image) }}" 
                                         alt="{{ $banner->title }}" 
                                         class="block md:hidden w-full h-auto max-h-[200px] object-contain mx-auto">
                                    @elseif($banner->desktop_image)
                                    <!-- Fallback to desktop image if no mobile image -->
                                    <img src="{{ Storage::disk('public')->url($banner->desktop_image) }}" 
                                         alt="{{ $banner->title }}" 
                                         class="block md:hidden w-full h-auto max-h-[200px] object-contain mx-auto">
                                    @endif
                                @if($banner->link)
                                </a>
                                @else
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @if($banners->count() > 1)
                <!-- Navigation Arrows -->
                <button id="prev-btn" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-black/30 rounded-full flex items-center justify-center text-white hover:bg-black/50 transition-colors z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button id="next-btn" class="absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-black/30 rounded-full flex items-center justify-center text-white hover:bg-black/50 transition-colors z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
                @endif
            </div>

            @if($banners->count() > 1)
            <!-- Carousel Indicators -->
            <div class="carousel-indicators mt-6">
                @foreach($banners as $banner)
                <div class="carousel-indicator {{ $loop->first ? 'active' : '' }}" data-slide="{{ $loop->index }}"></div>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Featured Categories -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Destaques</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-5 lg:grid-cols-10 gap-4">
                @forelse($highlights as $highlight)
                <a href="{{ $highlight->link ?: '#' }}" class="product-card bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:shadow-md transition-all">
                    <div class="relative">
                        @if($highlight->image)
                            <img src="{{ Storage::disk('public')->url($highlight->image) }}" alt="{{ $highlight->title }}" class="w-16 h-16 mx-auto mb-2 rounded object-cover">
                        @else
                            <img src="{{ Vite::asset('resources/images/destaques.jpg') }}" alt="{{ $highlight->title }}" class="w-16 h-16 mx-auto mb-2 rounded object-cover">
                        @endif
                        @if($highlight->discount_text)
                            <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $highlight->discount_text }}</div>
                        @endif
                    </div>
                    <p class="text-xs text-gray-600 font-medium">{{ $highlight->title }}</p>
                </a>
                @empty
                <div class="col-span-full text-center text-gray-500 py-8">
                    <p>Nenhum destaque cadastrado no momento.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Top Discounted Products -->
    @if($topDiscountedProducts->isNotEmpty())
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Maiores Descontos do Momento</h2>
            <p class="text-gray-600 mb-8">Confira os produtos com os melhores descontos disponíveis agora</p>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($topDiscountedProducts as $product)
                    <a href="{{ route('product.show', ['slug' => $product->permalink, 'id' => $product->id]) }}" 
                       class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-4 group border border-gray-200">
                        @if($product->image_url)
                            <div class="aspect-square mb-3 relative overflow-hidden rounded-lg bg-gray-50">
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-200"
                                     loading="lazy">
                                @if($product->discount_percentage)
                                    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
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
                                    <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
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

                            @if($product->discount_percentage && $product->discount_percentage > 1)
                                <div class="flex items-center mt-2">
                                    <div class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded">
                                        {{ $product->discount_percentage }}% OFF
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($product->store)
                            <div class="mt-2 pt-2 border-t border-gray-100">
                                <div class="flex items-center text-xs text-gray-600">
                                    @if(isset($product->store->logo_url))
                                        <img src="{{ $product->store->logo_url }}" 
                                             alt="{{ $product->store->name }}" 
                                             class="w-4 h-4 object-contain mr-1">
                                    @endif
                                    <span class="truncate">{{ $product->store->name }}</span>
                                </div>
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Newsletter Subscription -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Receba as melhores ofertas</h3>
            <p class="text-gray-600 mb-6">Cadastre-se e seja o primeiro a saber sobre promoções e descontos exclusivos</p>
            <form id="newsletter-form" class="max-w-md mx-auto">
                @csrf
                <div class="flex">
                    <input 
                        type="email" 
                        id="newsletter-email"
                        name="email"
                        placeholder="Seu e-mail" 
                        required
                        class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button 
                        type="submit"
                        id="newsletter-submit"
                        class="bg-blue-500 text-white px-6 py-3 rounded-r-lg hover:bg-blue-600 transition-colors font-medium">
                        Cadastrar
                    </button>
                </div>
                <div id="newsletter-message" class="mt-3 text-sm hidden"></div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Carousel functionality
    document.addEventListener('DOMContentLoaded', function() {
        const carouselSlides = document.querySelector('.carousel-slides');
        const indicators = document.querySelectorAll('.carousel-indicator');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        
        // Check if carousel elements exist (no banners = no carousel)
        if (!carouselSlides || indicators.length === 0) {
            return;
        }
        
        let currentSlide = 0;
        const totalSlides = indicators.length;
        let autoSlideInterval;
        
        // Function to update carousel position
        function updateCarousel() {
            const translateX = -currentSlide * 100;
            carouselSlides.style.transform = `translateX(${translateX}%)`;
            
            // Update indicators
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentSlide);
            });
        }
        
        // Function to go to next slide
        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }
        
        // Function to go to previous slide
        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }
        
        // Function to go to specific slide
        function goToSlide(index) {
            currentSlide = index;
            updateCarousel();
        }
        
        // Function to start auto-slide (only if more than 1 slide)
        function startAutoSlide() {
            if (totalSlides > 1) {
                autoSlideInterval = setInterval(nextSlide, 5000);
            }
        }
        
        // Function to stop auto-slide
        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
        }
        
        // Event listeners for navigation buttons (only if they exist)
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                stopAutoSlide();
                nextSlide();
                startAutoSlide();
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                stopAutoSlide();
                prevSlide();
                startAutoSlide();
            });
        }
        
        // Event listeners for indicators
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                stopAutoSlide();
                goToSlide(index);
                startAutoSlide();
            });
        });
        
        // Pause auto-slide on hover
        const carouselContainer = document.querySelector('.carousel-container');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', stopAutoSlide);
            carouselContainer.addEventListener('mouseleave', startAutoSlide);
            
            // Touch/swipe support for mobile
            let touchStartX = 0;
            let touchEndX = 0;
            
            carouselContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            carouselContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
            
            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchStartX - touchEndX;
                
                if (Math.abs(diff) > swipeThreshold) {
                    stopAutoSlide();
                    if (diff > 0) {
                        nextSlide(); // Swipe left - next slide
                    } else {
                        prevSlide(); // Swipe right - previous slide
                    }
                    startAutoSlide();
                }
            }
        }
        
        // Keyboard navigation (only if more than 1 slide)
        if (totalSlides > 1) {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') {
                    stopAutoSlide();
                    prevSlide();
                    startAutoSlide();
                } else if (e.key === 'ArrowRight') {
                    stopAutoSlide();
                    nextSlide();
                    startAutoSlide();
                }
            });
        }
        
        // Initialize carousel
        updateCarousel();
        startAutoSlide();
    });

    // Newsletter subscription form handling
    document.getElementById('newsletter-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const emailInput = document.getElementById('newsletter-email');
        const submitBtn = document.getElementById('newsletter-submit');
        const messageDiv = document.getElementById('newsletter-message');
        
        // Disable form during submission
        submitBtn.disabled = true;
        submitBtn.textContent = 'Cadastrando...';
        messageDiv.classList.add('hidden');
        
        try {
            const formData = new FormData(form);
            
            const response = await fetch('{{ route('newsletter.subscribe') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: emailInput.value
                })
            });
            
            const data = await response.json();
            
            // Show message
            messageDiv.classList.remove('hidden');
            
            if (data.success) {
                messageDiv.textContent = data.message;
                messageDiv.classList.remove('text-red-600');
                messageDiv.classList.add('text-green-600');
                emailInput.value = '';
            } else {
                messageDiv.textContent = data.message;
                messageDiv.classList.remove('text-green-600');
                messageDiv.classList.add('text-red-600');
            }
            
        } catch (error) {
            messageDiv.classList.remove('hidden', 'text-green-600');
            messageDiv.classList.add('text-red-600');
            messageDiv.textContent = 'Ocorreu um erro. Por favor, tente novamente.';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Cadastrar';
        }
    });
</script>
@endpush