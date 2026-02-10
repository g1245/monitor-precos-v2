@extends('layouts.app')

@section('title', 'Recuperar Senha - Monitor de Preços')

@section('content')
<div class="flex items-start justify-center bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900">
                Recuperar senha
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Informe seu e-mail para receber o link de recuperação
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form class="mt-6 space-y-6" action="{{ route('auth.recovery') }}" method="POST">
            @csrf
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                           placeholder="seu@email.com" 
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Enviar link de recuperação
                </button>

                <div class="text-center">
                    <a href="{{ route('auth.login') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Voltar para login
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
