<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Monitor de Preços - Encontre os melhores preços!')</title>
    <meta name="description" content="@yield('description', 'Compare preços de produtos de lojas virtuais de todo o Brasil e encontre as melhores ofertas.')">
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-primary shadow-md">
        <div class="container mx-auto px-4">
            <!-- Mobile Layout -->
            <div class="md:hidden">
                <!-- Top Row: Logo + Account -->
                <div class="flex items-center justify-between py-3">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-white">
                            Monitor de Preços
                        </h1>
                    </div>

                    <!-- Account Icon -->
                    <div class="text-white">
                        <div class="cursor-pointer hover:text-yellow-300 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Search -->
                <div class="pb-3">
                    <div class="relative">
                        <input type="search" 
                               placeholder="Buscar produtos ou departamentos" 
                               class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Layout -->
            <div class="hidden md:flex items-center justify-between py-3">
                <!-- Logo -->
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-white">
                        Monitor de Preços
                    </h1>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-8">
                    <div class="relative">
                        <input type="search" 
                               placeholder="Buscar produtos ou departamentos" 
                               class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- User Actions -->
                <div class="flex items-center space-x-4">
                    <!-- Login/Account -->
                    <div class="text-white text-sm">
                        <div class="flex items-center space-x-1 cursor-pointer hover:text-blue-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <div>
                                <div class="font-medium">Olá, faça seu login</div>
                                <div class="text-xs">ou cadastre-se</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Menu -->
    <nav class="bg-blue-800 text-white shadow-lg relative">
        <div class="container mx-auto px-4">
            <!-- Mobile Layout -->
            <div class="md:hidden py-3">
                <button id="departmentsBtn" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-blue-600 rounded hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span class="font-medium">Navegue por departamentos</span>
                    <svg id="chevronIcon" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>

            <!-- Desktop Layout -->
            <div class="hidden md:flex items-center justify-between py-3">
                <!-- Departments Menu -->
                <div class="flex items-center space-x-8">
                    <div class="relative">
                        <button id="departmentsBtnDesktop" class="flex items-center space-x-2 px-4 py-2 bg-blue-600 rounded hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <span class="font-medium">Navegue por departamentos</span>
                            <svg id="chevronIconDesktop" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Menu Items -->
                    <div class="flex items-center space-x-6">
                        <a href="#" class="flex items-center space-x-1 hover:text-blue-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            <span>Cupons</span>
                        </a>
                        <a href="#" class="flex items-center space-x-1 hover:text-blue-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <span>Super Ofertas</span>
                        </a>
                        <a href="#" class="flex items-center space-x-1 hover:text-blue-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <span>Sites parceiros</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departments Dropdown Menu - Desktop -->
        <div id="departmentsMenu" class="hidden absolute top-full left-0 w-full bg-white text-gray-800 shadow-xl z-50 border-t-2 border-blue-600">
            <div class="container mx-auto px-4 py-6">
                <div class="grid grid-cols-5 gap-8">
                    <!-- Eletrônicos -->
                    <div>
                        <h3 class="font-bold text-blue-800 mb-3 pb-1 border-b border-gray-200">eletrônicos</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{route('departament.index', ['alias' => 'audio-e-video', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">áudio e vídeo</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'celulares-e-smartphones', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">celulares e smartphones</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'computadores-e-informatica', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">computadores e informática</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'consoles-e-games', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">consoles e games</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'tablets', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">tablets</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'telefonia-fixa', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">telefonia fixa</a></li>
                        </ul>
                    </div>

                    <!-- Casa -->
                    <div>
                        <h3 class="font-bold text-blue-800 mb-3 pb-1 border-b border-gray-200">casa</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{route('departament.index', ['alias' => 'climatizacao', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">climatização</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'eletrodomesticos', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">eletrodomésticos</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'cama-mesa-e-banho', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">cama, mesa e banho</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'construcao-e-ferramentas', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">construção e ferramentas</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'decoracao', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">decoração</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'eletroportateis', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">eletroportáteis</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'limpeza', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">limpeza</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'moveis', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">móveis</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'utilidades-domesticas', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">utilidades domésticas</a></li>
                        </ul>
                    </div>

                    <!-- Lazer e Entretenimento -->
                    <div>
                        <h3 class="font-bold text-blue-800 mb-3 pb-1 border-b border-gray-200">lazer e entretenimento</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{route('departament.index', ['alias' => 'artes-e-artesanato', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">artes e artesanato</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'artigos-de-festas', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">artigos de festas</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'instrumentos-musicais', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">instrumentos musicais</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'livros', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">livros</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'musica-filmes-e-series', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">música, filmes e séries</a></li>
                        </ul>

                        <h3 class="font-bold text-blue-800 mb-3 pb-1 border-b border-gray-200 mt-6">pra você e sua família</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{route('departament.index', ['alias' => 'alimentos-e-bebidas', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">alimentos e bebidas</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'automotivo', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">automotivo</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'bebes-e-higiene-infantil', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">bebês e higiene infantil</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'brinquedos', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">brinquedos</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'malas-mochilas-e-acessorios', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">malas, mochilas e acessórios</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'papelaria', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">papelaria</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'pet-shop', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">pet shop</a></li>
                        </ul>
                    </div>

                    <!-- Esporte, Saúde e Bem-estar -->
                    <div>
                        <h3 class="font-bold text-blue-800 mb-3 pb-1 border-b border-gray-200">esporte, saúde e bem-estar</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{route('departament.index', ['alias' => 'esporte-fitness-e-lazer', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">esporte, fitness e lazer</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'saude-e-bem-estar', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">saúde e bem-estar</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'suplementos-e-vitaminas', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">suplementos e vitaminas</a></li>
                        </ul>

                        <h3 class="font-bold text-blue-800 mb-3 pb-1 border-b border-gray-200 mt-6">moda e beleza</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{route('departament.index', ['alias' => 'beleza-e-perfumaria', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">beleza e perfumaria</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'cabelos', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">cabelos</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'calcados', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">calçados</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'moda', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">moda</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'relogios-e-joias', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">relógios e joias</a></li>
                        </ul>
                    </div>

                    <!-- Confira Também -->
                    <div>
                        <h3 class="font-bold text-blue-800 mb-3 pb-1 border-b border-gray-200">confira também</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{route('departament.index', ['alias' => 'agro-industria-e-comercio', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">agro, indústria e comércio</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'vale-presente', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">vale presente</a></li>
                            <li><a href="{{route('departament.index', ['alias' => 'gift-card', 'permalink' => uniqid()])}}" class="text-gray-600 hover:text-blue-600 transition-colors">gift card</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Departments Menu - Full Screen -->
    <div id="mobileDepartmentsMenu" class="hidden fixed inset-0 bg-white z-50 md:hidden overflow-y-auto">
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="bg-blue-800 text-white p-4 flex items-center justify-between">
                <h2 class="text-lg font-bold">Departamentos</h2>
                <button id="closeMobileMenu" class="text-white hover:text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Categories -->
            <div class="flex-1 p-4">
                <!-- Eletrônicos -->
                <div class="mb-6">
                    <h3 class="font-bold text-blue-800 mb-3 text-lg border-b border-gray-200 pb-2">eletrônicos</h3>
                    <ul class="space-y-3">
                        <li><a href="{{route('departament.index', ['alias' => 'audio-e-video', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">áudio e vídeo</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'celulares-e-smartphones', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">celulares e smartphones</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'computadores-e-informatica', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">computadores e informática</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'consoles-e-games', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">consoles e games</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'tablets', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">tablets</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'telefonia-fixa', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">telefonia fixa</a></li>
                    </ul>
                </div>

                <!-- Casa -->
                <div class="mb-6">
                    <h3 class="font-bold text-blue-800 mb-3 text-lg border-b border-gray-200 pb-2">casa</h3>
                    <ul class="space-y-3">
                        <li><a href="{{route('departament.index', ['alias' => 'climatizacao', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">climatização</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'eletrodomesticos', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">eletrodomésticos</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'cama-mesa-e-banho', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">cama, mesa e banho</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'construcao-e-ferramentas', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">construção e ferramentas</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'decoracao', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">decoração</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'eletroportateis', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">eletroportáteis</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'limpeza', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">limpeza</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'moveis', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">móveis</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'utilidades-domesticas', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">utilidades domésticas</a></li>
                    </ul>
                </div>

                <!-- Lazer e Entretenimento -->
                <div class="mb-6">
                    <h3 class="font-bold text-blue-800 mb-3 text-lg border-b border-gray-200 pb-2">lazer e entretenimento</h3>
                    <ul class="space-y-3">
                        <li><a href="{{route('departament.index', ['alias' => 'artes-e-artesanato', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">artes e artesanato</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'artigos-de-festas', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">artigos de festas</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'instrumentos-musicais', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">instrumentos musicais</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'livros', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">livros</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'musica-filmes-e-series', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">música, filmes e séries</a></li>
                    </ul>
                </div>

                <!-- Pra Você e Sua Família -->
                <div class="mb-6">
                    <h3 class="font-bold text-blue-800 mb-3 text-lg border-b border-gray-200 pb-2">pra você e sua família</h3>
                    <ul class="space-y-3">
                        <li><a href="{{route('departament.index', ['alias' => 'alimentos-e-bebidas', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">alimentos e bebidas</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'automotivo', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">automotivo</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'bebes-e-higiene-infantil', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">bebês e higiene infantil</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'brinquedos', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">brinquedos</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'malas-mochilas-e-acessorios', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">malas, mochilas e acessórios</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'papelaria', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">papelaria</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'pet-shop', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">pet shop</a></li>
                    </ul>
                </div>

                <!-- Esporte, Saúde e Bem-estar -->
                <div class="mb-6">
                    <h3 class="font-bold text-blue-800 mb-3 text-lg border-b border-gray-200 pb-2">esporte, saúde e bem-estar</h3>
                    <ul class="space-y-3">
                        <li><a href="{{route('departament.index', ['alias' => 'esporte-fitness-e-lazer', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">esporte, fitness e lazer</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'saude-e-bem-estar', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">saúde e bem-estar</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'suplementos-e-vitaminas', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">suplementos e vitaminas</a></li>
                    </ul>
                </div>

                <!-- Moda e Beleza -->
                <div class="mb-6">
                    <h3 class="font-bold text-blue-800 mb-3 text-lg border-b border-gray-200 pb-2">moda e beleza</h3>
                    <ul class="space-y-3">
                        <li><a href="{{route('departament.index', ['alias' => 'beleza-e-perfumaria', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">beleza e perfumaria</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'cabelos', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">cabelos</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'calcados', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">calçados</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'moda', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">moda</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'relogios-e-joias', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">relógios e joias</a></li>
                    </ul>
                </div>

                <!-- Confira Também -->
                <div class="mb-6">
                    <h3 class="font-bold text-blue-800 mb-3 text-lg border-b border-gray-200 pb-2">confira também</h3>
                    <ul class="space-y-3">
                        <li><a href="{{route('departament.index', ['alias' => 'agro-industria-e-comercio', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">agro, indústria e comércio</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'vale-presente', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">vale presente</a></li>
                        <li><a href="{{route('departament.index', ['alias' => 'gift-card', 'permalink' => uniqid()])}}" class="text-gray-700 block py-1">gift card</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Promo Banner -->
    <div class="bg-blue-900 text-white py-2">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
                <span class="text-sm">O Monitor de Preços você encontra as melhores ofertas do Brasil.</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-lg font-bold mb-4">Monitor de Preços</h3>
                    <p class="text-gray-300 text-sm mb-4">
                        Compare preços de produtos de lojas virtuais de todo o Brasil e encontre as melhores ofertas.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="font-semibold mb-4">Links Rápidos</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Sobre Nós</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Como Funciona</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Lojas Parceiras</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Contato</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div>
                    <h4 class="font-semibold mb-4">Categorias</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Eletrônicos</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Celulares</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Informática</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Eletrodomésticos</a></li>
                        <li><a href="#" class="hover:text-blue-500 transition-colors">Casa e Decoração</a></li>
                    </ul>
                </div>

                <!-- Help -->
                <div>
                    <h4 class="font-semibold mb-4">Ajuda</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Central de Ajuda</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Política de Privacidade</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Termos de Uso</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-blue-400 transition-colors">Suporte</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-blue-800 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Monitor de Preços. Todos os direitos reservados. Group 1245 LTDA - 52.171.773/0001-34</p>
            </div>
        </div>
    </footer>

    @livewireScripts
    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departmentsBtnMobile = document.getElementById('departmentsBtn');
            const departmentsBtnDesktop = document.getElementById('departmentsBtnDesktop');
            const departmentsMenu = document.getElementById('departmentsMenu');
            const mobileDepartmentsMenu = document.getElementById('mobileDepartmentsMenu');
            const closeMobileMenu = document.getElementById('closeMobileMenu');
            const chevronIcon = document.getElementById('chevronIcon');
            const chevronIconDesktop = document.getElementById('chevronIconDesktop');
            
            let isMenuOpen = false;

            // Mobile departments menu toggle
            if (departmentsBtnMobile) {
                departmentsBtnMobile.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isMenuOpen = !isMenuOpen;
                    
                    if (isMenuOpen) {
                        mobileDepartmentsMenu.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                        chevronIcon.style.transform = 'rotate(180deg)';
                    } else {
                        mobileDepartmentsMenu.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                        chevronIcon.style.transform = 'rotate(0deg)';
                    }
                });
            }

            // Desktop departments menu toggle
            if (departmentsBtnDesktop) {
                departmentsBtnDesktop.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isMenuOpen = !isMenuOpen;
                    
                    if (isMenuOpen) {
                        departmentsMenu.classList.remove('hidden');
                        chevronIconDesktop.style.transform = 'rotate(180deg)';
                    } else {
                        departmentsMenu.classList.add('hidden');
                        chevronIconDesktop.style.transform = 'rotate(0deg)';
                    }
                });
            }

            // Close mobile menu
            if (closeMobileMenu) {
                closeMobileMenu.addEventListener('click', function() {
                    mobileDepartmentsMenu.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    chevronIcon.style.transform = 'rotate(0deg)';
                    isMenuOpen = false;
                });
            }

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (isMenuOpen) {
                    const clickedInsideDesktopBtn = departmentsBtnDesktop && departmentsBtnDesktop.contains(e.target);
                    const clickedInsideMobileBtn = departmentsBtnMobile && departmentsBtnMobile.contains(e.target);
                    const clickedInsideDesktopMenu = departmentsMenu && departmentsMenu.contains(e.target);
                    const clickedInsideMobileMenu = mobileDepartmentsMenu && mobileDepartmentsMenu.contains(e.target);
                    
                    if (!clickedInsideDesktopBtn && !clickedInsideMobileBtn && !clickedInsideDesktopMenu && !clickedInsideMobileMenu) {
                        departmentsMenu.classList.add('hidden');
                        mobileDepartmentsMenu.classList.add('hidden');
                        if (chevronIcon) chevronIcon.style.transform = 'rotate(0deg)';
                        if (chevronIconDesktop) chevronIconDesktop.style.transform = 'rotate(0deg)';
                        document.body.style.overflow = 'auto';
                        isMenuOpen = false;
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                // Close all menus on resize
                departmentsMenu.classList.add('hidden');
                mobileDepartmentsMenu.classList.add('hidden');
                if (chevronIcon) chevronIcon.style.transform = 'rotate(0deg)';
                if (chevronIconDesktop) chevronIconDesktop.style.transform = 'rotate(0deg)';
                document.body.style.overflow = 'auto';
                isMenuOpen = false;
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && isMenuOpen) {
                    departmentsMenu.classList.add('hidden');
                    mobileDepartmentsMenu.classList.add('hidden');
                    if (chevronIcon) chevronIcon.style.transform = 'rotate(0deg)';
                    if (chevronIconDesktop) chevronIconDesktop.style.transform = 'rotate(0deg)';
                    document.body.style.overflow = 'auto';
                    isMenuOpen = false;
                }
            });
        });
    </script>
</body>
</html>