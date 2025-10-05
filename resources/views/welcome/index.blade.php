@extends('layouts.app')
@section('title', 'Monitor de Preços - Compare preços e encontre as melhores ofertas')
@section('description', 'Compare preços de produtos de lojas virtuais de todo o Brasil e encontre as melhores ofertas.')
@section('content')
    <!-- Hero Banner -->
    <section class="banner-gradient py-12 relative overflow-hidden">
        <div class="container mx-auto px-4">
            <div class="relative bg-white/10 backdrop-blur-sm rounded-2xl p-8 flex items-center justify-between min-h-[300px]">
                <div class="w-full flex justify-center items-center p-4">
                    <img src="{{ Vite::asset('resources/images/banner.jpg') }}" 
                         alt="Banner promocional" 
                         class="max-w-full h-auto rounded-lg shadow-lg">
                </div>

                <!-- Navigation Arrows -->
                <button class="absolute left-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-black/30 rounded-full flex items-center justify-center text-white hover:bg-black/50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <button class="absolute right-4 top-1/2 transform -translate-y-1/2 w-12 h-12 bg-black/30 rounded-full flex items-center justify-center text-white hover:bg-black/50 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <!-- Carousel Indicators -->
            <div class="carousel-indicators mt-6">
                <div class="carousel-indicator active"></div>
                <div class="carousel-indicator"></div>
                <div class="carousel-indicator"></div>
                <div class="carousel-indicator"></div>
                <div class="carousel-indicator"></div>
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
    // Simple carousel functionality
    document.addEventListener('DOMContentLoaded', function() {
        const indicators = document.querySelectorAll('.carousel-indicator');
        let currentSlide = 0;
        
        function updateIndicators() {
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentSlide);
            });
        }
        
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                updateIndicators();
            });
        });
        
        // Auto-advance carousel every 5 seconds
        setInterval(() => {
            currentSlide = (currentSlide + 1) % indicators.length;
            updateIndicators();
        }, 5000);
    });
</script>
@endpush