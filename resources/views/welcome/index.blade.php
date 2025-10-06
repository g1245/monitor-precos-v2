@extends('layouts.app')
@section('title', 'Monitor de Preços - Compare preços e encontre as melhores ofertas')
@section('description', 'Compare preços de produtos de lojas virtuais de todo o Brasil e encontre as melhores ofertas.')
@section('content')
    <!-- Hero Banner Carousel -->
    <section class="banner-gradient py-12 relative overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="relative bg-white/10 backdrop-blur-sm rounded-2xl p-8 overflow-hidden min-h-[300px]">
                <!-- Carousel Container -->
                <!-- Each banner contains two images: one for desktop (hidden md:block) and one for mobile (block md:hidden) -->
                <div class="carousel-container relative">
                    <div class="carousel-wrapper overflow-hidden rounded-lg">
                        <div class="carousel-slides flex transition-transform duration-750 ease-in-out">
                            <!-- Banner 1 -->
                            <div class="carousel-slide w-full flex-shrink-0">
                                <a href="#" class="block">
                                    <!-- Desktop Image -->
                                    <img src="{{ Vite::asset('resources/images/banner.png') }}" 
                                         alt="Banner promocional 1" 
                                         class="hidden md:block w-full h-auto max-h-[250px] object-contain mx-auto">
                                    <!-- Mobile Image -->
                                    <img src="{{ Vite::asset('resources/images/banner-mobile.png') }}" 
                                         alt="Banner promocional 1" 
                                         class="block md:hidden w-full h-auto max-h-[200px] object-contain mx-auto">
                                </a>
                            </div>
                            
                            <!-- Banner 2 -->
                            <div class="carousel-slide w-full flex-shrink-0">
                                <a href="#" class="block">
                                    <!-- Desktop Image -->
                                    <img src="{{ Vite::asset('resources/images/banner-2.png') }}" 
                                         alt="Banner promocional 2" 
                                         class="hidden md:block w-full h-auto max-h-[250px] object-contain mx-auto">
                                    <!-- Mobile Image -->
                                    <img src="{{ Vite::asset('resources/images/banner-2-mobile.png') }}" 
                                         alt="Banner promocional 2" 
                                         class="block md:hidden w-full h-auto max-h-[200px] object-contain mx-auto">
                                </a>
                            </div>
                            
                            <!-- Banner 3 -->
                            <div class="carousel-slide w-full flex-shrink-0">
                                <a href="#" class="block">
                                    <!-- Desktop Image -->
                                    <img src="{{ Vite::asset('resources/images/banner-3.png') }}" 
                                         alt="Banner promocional 3" 
                                         class="hidden md:block w-full h-auto max-h-[250px] object-contain mx-auto">
                                    <!-- Mobile Image -->
                                    <img src="{{ Vite::asset('resources/images/banner-3-mobile.png') }}" 
                                         alt="Banner promocional 3" 
                                         class="block md:hidden w-full h-auto max-h-[200px] object-contain mx-auto">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

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
            </div>

            <!-- Carousel Indicators -->
            <div class="carousel-indicators mt-6">
                <div class="carousel-indicator active" data-slide="0"></div>
                <div class="carousel-indicator" data-slide="1"></div>
                <div class="carousel-indicator" data-slide="2"></div>
            </div>
        </div>
    </section>

    <!-- Featured Categories -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">Destaques</h2>
            
            <div class="grid grid-cols-2 md:grid-cols-5 lg:grid-cols-10 gap-4">
                @for($i = 0; $i < 10; $i++)
                <div class="product-card bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center hover:shadow-md transition-all cursor-pointer">
                    <div class="relative">
                        <img src="{{ Vite::asset('resources/images/destaques.jpg') }}" alt="Ar e ventilação" class="w-16 h-16 mx-auto mb-2 rounded">
                        <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">20%</div>
                    </div>
                    <p class="text-xs text-gray-600 font-medium">Ar e<br>ventilação</p>
                </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Newsletter Subscription -->
    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Receba as melhores ofertas</h3>
            <p class="text-gray-600 mb-6">Cadastre-se e seja o primeiro a saber sobre promoções e descontos exclusivos</p>
            <div class="max-w-md mx-auto flex">
                <input type="email" placeholder="Seu e-mail" class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button class="bg-blue-500 text-white px-6 py-3 rounded-r-lg hover:bg-blue-600 transition-colors font-medium">
                    Cadastrar
                </button>
            </div>
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
        
        // Function to start auto-slide
        function startAutoSlide() {
            autoSlideInterval = setInterval(nextSlide, 5000);
        }
        
        // Function to stop auto-slide
        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
        }
        
        // Event listeners for navigation buttons
        nextBtn.addEventListener('click', () => {
            stopAutoSlide();
            nextSlide();
            startAutoSlide();
        });
        
        prevBtn.addEventListener('click', () => {
            stopAutoSlide();
            prevSlide();
            startAutoSlide();
        });
        
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
        
        // Keyboard navigation
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
        
        // Initialize carousel
        updateCarousel();
        startAutoSlide();
    });
</script>
@endpush