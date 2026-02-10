@extends('layouts.app')

@section('title', 'Criar Conta - Monitor de Preços')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Criar nova conta
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <a href="{{ route('auth.login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                    entrar na sua conta existente
                </a>
            </p>
        </div>

        <form id="register-form" class="mt-8 space-y-6" action="{{ route('auth.register') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input id="name" name="name" type="text" autocomplete="name" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border @error('name') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                           placeholder="Seu nome completo" 
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border @error('password') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                           placeholder="Mínimo 8 caracteres">
                    
                    <!-- Password Strength Indicator -->
                    <div id="password-strength" class="mt-2 hidden">
                        <div class="flex items-center space-x-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div id="password-strength-bar" class="h-full"></div>
                            </div>
                            <span id="password-strength-text" class="text-xs font-medium"></span>
                        </div>
                        <p id="password-strength-message" class="mt-1 text-xs"></p>
                    </div>
                    
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

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Criar conta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const passwordStrengthDiv = document.getElementById('password-strength');
    const passwordStrengthBar = document.getElementById('password-strength-bar');
    const passwordStrengthText = document.getElementById('password-strength-text');
    const passwordStrengthMessage = document.getElementById('password-strength-message');
    const registerForm = document.getElementById('register-form');
    
    // Cache media query for performance
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    
    let currentPasswordStrengthLevel = 0;
    
    /**
     * Calculate password strength
     * Returns: {level: 0|1|2, criteria: {...}, score: number}
     */
    function calculatePasswordStrength(password) {
        let strength = 0;
        let criteria = {
            length: false,
            lowercase: false,
            uppercase: false,
            numbers: false,
            special: false
        };
        
        if (!password) {
            return { level: 0, criteria, score: 0 };
        }
        
        // Check length (minimum 8 characters)
        if (password.length >= 8) {
            criteria.length = true;
            strength += 1;
        }
        
        // Check for lowercase letters
        if (/[a-z]/.test(password)) {
            criteria.lowercase = true;
            strength += 1;
        }
        
        // Check for uppercase letters
        if (/[A-Z]/.test(password)) {
            criteria.uppercase = true;
            strength += 1;
        }
        
        // Check for numbers
        if (/[0-9]/.test(password)) {
            criteria.numbers = true;
            strength += 1;
        }
        
        // Check for special characters
        if (/[^A-Za-z0-9]/.test(password)) {
            criteria.special = true;
            strength += 1;
        }
        
        // Determine overall strength level
        // Weak: less than 3 criteria met
        // Medium: 3 criteria met
        // Strong: 4 or more criteria met
        let level = 0;
        if (strength >= 4) {
            level = 2; // Strong
        } else if (strength >= 3) {
            level = 1; // Medium
        } else {
            level = 0; // Weak
        }
        
        return { level, criteria, score: strength };
    }
    
    /**
     * Update password strength indicator
     */
    function updatePasswordStrength(password) {
        if (!password || password.length === 0) {
            passwordStrengthDiv.classList.add('hidden');
            currentPasswordStrengthLevel = 0;
            return;
        }
        
        passwordStrengthDiv.classList.remove('hidden');
        
        const result = calculatePasswordStrength(password);
        currentPasswordStrengthLevel = result.level;
        
        // Respect prefers-reduced-motion for animations
        if (!prefersReducedMotion.matches) {
            passwordStrengthBar.style.transition = 'all 0.3s ease-in-out';
        } else {
            passwordStrengthBar.style.transition = 'none';
        }
        
        // Update visual indicator based on strength level
        if (result.level === 0) {
            // Weak password
            passwordStrengthBar.style.width = '33%';
            passwordStrengthBar.style.backgroundColor = '#ef4444'; // red-500
            passwordStrengthText.textContent = 'Fraca';
            passwordStrengthText.style.color = '#ef4444';
            
            const missing = [];
            if (!result.criteria.length) missing.push('mínimo 8 caracteres');
            if (!result.criteria.lowercase) missing.push('letras minúsculas');
            if (!result.criteria.uppercase) missing.push('letras maiúsculas');
            if (!result.criteria.numbers) missing.push('números');
            if (!result.criteria.special) missing.push('caracteres especiais');
            
            // Format list with proper grammar: "item1, item2 e item3"
            let missingText;
            if (missing.length === 1) {
                missingText = missing[0];
            } else if (missing.length === 2) {
                missingText = missing.join(' e ');
            } else {
                const lastItem = missing[missing.length - 1];
                const otherItems = missing.slice(0, -1);
                missingText = otherItems.join(', ') + ' e ' + lastItem;
            }
            
            passwordStrengthMessage.textContent = `Senha muito fraca. Adicione: ${missingText}.`;
            passwordStrengthMessage.style.color = '#ef4444';
        } else if (result.level === 1) {
            // Medium password
            passwordStrengthBar.style.width = '66%';
            passwordStrengthBar.style.backgroundColor = '#f59e0b'; // amber-500
            passwordStrengthText.textContent = 'Intermediária';
            passwordStrengthText.style.color = '#f59e0b';
            
            passwordStrengthMessage.textContent = 'Senha razoável. Para maior segurança, adicione mais caracteres variados.';
            passwordStrengthMessage.style.color = '#f59e0b';
        } else {
            // Strong password
            passwordStrengthBar.style.width = '100%';
            passwordStrengthBar.style.backgroundColor = '#22c55e'; // green-500
            passwordStrengthText.textContent = 'Forte';
            passwordStrengthText.style.color = '#22c55e';
            
            passwordStrengthMessage.textContent = 'Excelente! Senha forte e segura.';
            passwordStrengthMessage.style.color = '#22c55e';
        }
    }
    
    // Listen to password input changes
    passwordInput.addEventListener('input', function() {
        updatePasswordStrength(this.value);
    });
    
    // Prevent form submission if password is weak
    registerForm.addEventListener('submit', function(e) {
        if (currentPasswordStrengthLevel === 0 && passwordInput.value.length > 0) {
            e.preventDefault();
            // The indicator is already showing the weak password message
            // Scroll to the password field to ensure user sees the message
            passwordInput.scrollIntoView({ 
                behavior: prefersReducedMotion.matches ? 'auto' : 'smooth', 
                block: 'center' 
            });
            passwordInput.focus();
            return false;
        }
        
        // Allow submission for medium and strong passwords
        return true;
    });
});
</script>
@endpush
