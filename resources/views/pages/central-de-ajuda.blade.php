@extends('layouts.app')

@section('title', 'Central de Ajuda - Monitor de Preços')
@section('description', 'Encontre orientações e respostas para usar o Monitor de Preços.')

@section('content')
    <div class="bg-gray-50 min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Central de Ajuda</h1>
                <p class="mt-2 text-gray-600">Guias rápidos para encontrar produtos e comparar ofertas.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900">Tópicos</h2>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-lg border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900">Busca</h3>
                        <p class="mt-2 text-sm text-gray-700">Use termos simples e refine pela página de resultados.</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900">Comparação</h3>
                        <p class="mt-2 text-sm text-gray-700">Compare a oferta por loja e valide frete/prazo no destino.</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900">Conta</h3>
                        <p class="mt-2 text-sm text-gray-700">Salve itens e acompanhe produtos que você quer monitorar.</p>
                    </div>
                    <div class="rounded-lg border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900">Problemas</h3>
                        <p class="mt-2 text-sm text-gray-700">Se algo estiver errado, envie links e detalhes pelo Suporte.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Entre em Contato</h2>
                <p class="text-gray-600 mb-6">Preencha o formulário abaixo e nossa equipe entrará em contato em breve.</p>
                
                <form id="contact-form" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nome <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="contact-name" 
                            name="name" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Seu nome completo"
                        >
                    </div>

                    <div>
                        <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-1">
                            E-mail <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="contact-email" 
                            name="email" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="seu@email.com"
                        >
                    </div>

                    <div>
                        <label for="contact-phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Telefone
                        </label>
                        <input 
                            type="tel" 
                            id="contact-phone" 
                            name="phone"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="(00) 00000-0000"
                        >
                    </div>

                    <div>
                        <label for="contact-message" class="block text-sm font-medium text-gray-700 mb-1">
                            Mensagem <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="contact-message" 
                            name="message" 
                            rows="5" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                            placeholder="Descreva sua dúvida ou problema..."
                        ></textarea>
                    </div>

                    <div id="contact-message-display" class="hidden p-3 rounded-lg text-sm"></div>

                    <button 
                        type="submit" 
                        id="contact-submit"
                        class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Enviar Mensagem
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Contact form submission handling
    document.getElementById('contact-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('contact-submit');
        const messageDiv = document.getElementById('contact-message-display');
        
        // Get form data
        const formData = {
            name: document.getElementById('contact-name').value,
            email: document.getElementById('contact-email').value,
            phone: document.getElementById('contact-phone').value,
            message: document.getElementById('contact-message').value
        };
        
        // Disable form during submission
        submitBtn.disabled = true;
        submitBtn.textContent = 'Enviando...';
        messageDiv.classList.add('hidden');
        
        try {
            const response = await fetch('{{ route('contact-message.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            // Show message
            messageDiv.classList.remove('hidden');
            
            if (data.success) {
                messageDiv.textContent = data.message;
                messageDiv.classList.remove('bg-red-50', 'text-red-600');
                messageDiv.classList.add('bg-green-50', 'text-green-600');
                
                // Clear form on success
                form.reset();
            } else {
                messageDiv.textContent = data.message;
                messageDiv.classList.remove('bg-green-50', 'text-green-600');
                messageDiv.classList.add('bg-red-50', 'text-red-600');
            }
            
        } catch (error) {
            messageDiv.classList.remove('hidden', 'bg-green-50', 'text-green-600');
            messageDiv.classList.add('bg-red-50', 'text-red-600');
            messageDiv.textContent = 'Ocorreu um erro ao enviar a mensagem. Por favor, tente novamente.';
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Enviar Mensagem';
        }
    });
</script>
@endpush

