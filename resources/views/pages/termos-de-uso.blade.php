@extends('layouts.app')

@section('title', 'Termos de Uso - Monitor de Preços')
@section('description', 'Condições gerais de uso do Monitor de Preços (resumo).')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Termos de Uso</h1>
                <p class="mt-2 text-gray-600">Conteúdo informativo (versão resumida).</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <section>
                    <h2 class="text-xl font-semibold text-gray-900">Uso da plataforma</h2>
                    <p class="mt-3 text-gray-700 leading-relaxed">
                        Ao utilizar o Monitor de Preços, você concorda em usar a plataforma de forma responsável e em conformidade com a legislação aplicável.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900">Ofertas e compra</h2>
                    <p class="mt-3 text-gray-700 leading-relaxed">
                        O Monitor de Preços exibe ofertas e direciona para lojas. As condições finais (frete, prazo, disponibilidade e pagamento)
                        são definidas pela loja no momento da compra.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-semibold text-gray-900">Atualizações</h2>
                    <p class="mt-3 text-gray-700 leading-relaxed">
                        Os termos podem ser atualizados para refletir mudanças operacionais, legais ou melhorias da plataforma.
                    </p>
                </section>
            </div>
        </div>
    </div>
@endsection
