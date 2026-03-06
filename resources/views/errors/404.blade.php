@extends('layouts.app')

@section('title', 'Página não encontrada — Monitor de Preços')
@section('description', 'A página que você tentou acessar não existe ou foi removida. Volte para a página inicial ou explore nossas lojas parceiras.')

@section('content')
    <div class="bg-gray-50 flex-1 flex items-center py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-lg mx-auto text-center">

                {{-- Ilustração numérica --}}
                <div class="flex items-center justify-center gap-2 select-none">
                    <span class="text-9xl font-extrabold text-blue-600 leading-none">4</span>
                    <span class="text-9xl font-extrabold text-blue-800 leading-none">0</span>
                    <span class="text-9xl font-extrabold text-blue-600 leading-none">4</span>
                </div>

                {{-- Mensagem principal --}}
                <h1 class="mt-6 text-3xl font-bold text-gray-900">
                    Página não encontrada
                </h1>

                <p class="mt-3 text-base text-gray-500 leading-relaxed">
                    Ops! A página que você tentou acessar não existe, foi removida ou o endereço foi digitado incorretamente.
                </p>

                {{-- Atalhos --}}
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <a
                        href="{{ route('welcome') }}"
                        class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-150"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7A1 1 0 003 11h1v6a1 1 0 001 1h4v-4h2v4h4a1 1 0 001-1v-6h1a1 1 0 00.707-1.707l-7-7z" />
                        </svg>
                        Página inicial
                    </a>

                    <a
                        href="{{ route('stores.index') }}"
                        class="inline-flex items-center gap-2 rounded-md border border-blue-600 bg-white px-6 py-3 text-sm font-semibold text-blue-600 shadow-sm hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-150"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C4.78 11.01 5.345 12 6.279 12H15a1 1 0 000-2H6.875l.823-.822a1 1 0 00.218-1.051L6.546 4H18a1 1 0 00.96-1.282l-1-3A1 1 0 0017 0H4a1 1 0 00-1 1z" />
                            <path d="M16 16a2 2 0 11-4 0 2 2 0 014 0zM8 16a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Ver lojas parceiras
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
