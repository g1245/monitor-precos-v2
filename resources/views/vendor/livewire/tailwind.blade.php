<!-- Pagination with Tailwind CSS styling -->
<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col items-center space-y-4">
            <!-- Linha 1: Mensagem sobre os resultados -->
            <div class="w-full text-center">
                <p class="text-lg font-medium text-gray-700 leading-5">
                    Exibindo {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
                </p>
            </div>

            <!-- Linha 2: Controles de paginação -->
            <div class="w-full flex justify-center">
                <ul class="flex border border-gray-200 rounded-md overflow-hidden">
                    <!-- Previous Page Link -->
                    <li>
                        @if (!$paginator->onFirstPage())
                                <button wire:click="previousPage('{{ $paginator->getPageName() }}')" rel="prev" class="relative inline-flex items-center justify-center h-10 w-10 text-sm font-medium bg-white text-gray-700 border-r border-gray-200 hover:bg-primary hover:text-white focus:outline-none focus:ring-1 focus:ring-primary transition ease-in-out duration-150" aria-label="Previous">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <span class="relative inline-flex items-center justify-center h-10 w-10 text-sm font-medium text-gray-400 bg-white border-r border-gray-200 cursor-default" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </li>

                        <!-- Pagination Elements -->
                        @foreach ($elements as $element)
                            <!-- "Three Dots" Separator -->
                            @if (is_string($element))
                                <li>
                                    <span class="relative inline-flex items-center justify-center h-10 w-10 text-sm font-medium text-gray-700 bg-white border-r border-gray-200 cursor-default">{{ $element }}</span>
                                </li>
                            @endif

                            <!-- Array Of Links -->
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <li>
                                        @if ($page == $paginator->currentPage())
                                            <span class="relative inline-flex items-center justify-center h-10 w-10 text-sm font-medium text-white bg-primary cursor-default border-r border-gray-200" aria-current="page">{{ $page }}</span>
                                        @else
                                            <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="relative inline-flex items-center justify-center h-10 w-10 text-sm font-medium text-gray-700 bg-white border-r border-gray-200 hover:bg-gray-50 hover:text-primary focus:outline-none transition ease-in-out duration-150" aria-label="Go to page {{ $page }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </li>
                                @endforeach
                            @endif
                        @endforeach

                        <!-- Next Page Link -->
                        <li>
                            @if ($paginator->hasMorePages())
                                <button wire:click="nextPage('{{ $paginator->getPageName() }}')" rel="next" class="relative inline-flex items-center justify-center h-10 w-10 text-sm font-medium bg-white text-gray-700 hover:bg-primary hover:text-white focus:outline-none focus:ring-1 focus:ring-primary transition ease-in-out duration-150" aria-label="Next">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <span class="relative inline-flex items-center justify-center h-10 w-10 text-sm font-medium text-gray-400 bg-white cursor-default" aria-hidden="true">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Mobile pagination (simplified) -->
            <div class="flex flex-col items-center space-y-4 sm:hidden">
                <!-- Linha 1 móvel: Informação sobre resultados -->
                <div class="text-sm text-gray-700">
                    Exibindo {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
                </div>
                
                <!-- Linha 2 móvel: Controles de navegação -->
                <div class="flex items-center justify-between w-full">
                    <!-- Botão anterior -->
                    <div>
                        @if (!$paginator->onFirstPage())
                            <button wire:click="previousPage('{{ $paginator->getPageName() }}')" class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md bg-white text-primary border border-primary hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition ease-in-out duration-150" aria-label="Previous">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif
                    </div>
                    
                    <!-- Informação da página atual -->
                    <div class="flex items-center space-x-1">
                        @foreach ($elements as $element)
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $paginator->currentPage())
                                        <span class="relative inline-flex items-center justify-center w-8 h-8 text-sm font-medium text-white bg-primary border border-primary rounded-md">
                                            {{ $page }}
                                        </span>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </div>

                    <!-- Botão próximo -->
                    <div>
                        @if ($paginator->hasMorePages())
                            <button wire:click="nextPage('{{ $paginator->getPageName() }}')" class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md bg-white text-primary border border-primary hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition ease-in-out duration-150" aria-label="Next">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    @endif
</div>