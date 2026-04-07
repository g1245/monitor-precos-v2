@extends('layouts.app')
@section('title', 'Monitor de Preços - Compare preços e encontre as melhores ofertas')
@section('description', 'Compare preços de produtos de lojas virtuais de todo o Brasil e encontre as melhores ofertas.')
@section('content')
    <!-- Products Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <livewire:welcome-products :tab="$tab" />
        </div>
    </section>

    <!-- Newsletter Subscription -->
    <section class="py-12 bg-gray-100 mt-auto">
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
    if (!window.newsletterScriptLoaded) {
        window.newsletterScriptLoaded = true;
        const newsletterForm = document.getElementById('newsletter-form');

        if (newsletterForm) {
            let isSubmitting = false;

            newsletterForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (isSubmitting) {
                    return;
                }

                isSubmitting = true;

                const emailInput = document.getElementById('newsletter-email');
                const submitBtn = document.getElementById('newsletter-submit');
                const messageDiv = document.getElementById('newsletter-message');

                submitBtn.disabled = true;
                submitBtn.textContent = 'Cadastrando...';
                messageDiv.classList.add('hidden');

                try {
                    const response = await fetch('{{ route("newsletter.subscribe") }}', {
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
                    isSubmitting = false;
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Cadastrar';
                }
            });
        }
    }
</script>
@endpush
