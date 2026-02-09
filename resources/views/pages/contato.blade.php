@extends('layouts.app')

@section('title', 'Contato - Monitor de Preços')
@section('description', 'Entre em contato com o Monitor de Preços para dúvidas, sugestões ou suporte.')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Contato</h1>
                <p class="mt-2 text-gray-600">Dúvidas, sugestões ou solicitações.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900">Como podemos ajudar?</h2>
                    <p class="mt-4 text-gray-700 leading-relaxed">
                        Para agilizar o atendimento, envie o máximo de detalhes possível (links de produto/loja e prints quando necessário).
                    </p>
                    <p class="mt-4 text-gray-700 leading-relaxed">
                        Se o assunto for uma dúvida comum de uso, a Central de Ajuda e o FAQ costumam resolver mais rápido.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('pages.help-center') }}" class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Central de Ajuda
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900">Suporte</h2>
                    <p class="mt-4 text-gray-700 text-sm leading-relaxed">
                        Para solicitações técnicas e casos que exigem acompanhamento, use a página de Suporte.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
