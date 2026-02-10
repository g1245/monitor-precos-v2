@extends('layouts.app')

@section('title', 'Redefinir Senha - Monitor de Preços')

@section('content')
<div class="flex items-start justify-center bg-gray-50 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900">
                Redefinir senha
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Digite sua nova senha
            </p>
        </div>

        <form class="mt-6 space-y-6" action="{{ route('auth.password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Nova Senha</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border @error('password') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                               placeholder="Mínimo 8 caracteres">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Senha</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                               placeholder="Digite a senha novamente">
                    </div>
                </div>

                @error('email')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Redefinir senha
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
