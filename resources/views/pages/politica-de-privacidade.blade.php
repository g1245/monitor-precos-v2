@extends('layouts.app')

@section('title', 'Política de Privacidade - Monitor de Preços')
@section('description', 'Informações gerais sobre privacidade e uso de dados no Monitor de Preços.')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Política de Privacidade</h1>
                <p class="mt-2 text-gray-600">Conteúdo informativo (versão resumida).</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900">Visão geral</h2>
                    <p class="mt-3 text-gray-700 leading-relaxed">
                        Esta página descreve, de forma resumida, como dados podem ser utilizados para fornecer recursos da plataforma.
                        O conteúdo pode ser atualizado para refletir as práticas e requisitos aplicáveis.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900">Dados utilizados</h2>
                    <p class="mt-3 text-gray-700 leading-relaxed">
                        Dados de conta e preferências podem ser necessários para funcionalidades como favoritos e histórico.
                        Dados exibidos de produtos e lojas são apresentados para facilitar comparação.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900">Dúvidas</h2>
                    <p class="mt-3 text-gray-700 leading-relaxed">
                        Se você tiver dúvidas sobre privacidade, entre em contato pela página de Suporte.
                    </p>
                    <a href="{{ route('pages.support') }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                        Ir para Suporte
                    </a>
                </section>
            </div>
        </div>
    </div>
@endsection
