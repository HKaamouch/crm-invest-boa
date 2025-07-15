<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Connexion</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .floating-animation {
            animation: float 8s ease-in-out infinite;
        }
        .floating-animation-delayed {
            animation: float 8s ease-in-out infinite;
            animation-delay: -4s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }
    </style>
</head>
<body class="font-inter text-gray-900 antialiased">
<div class="min-h-screen flex bg-white">
    <!-- Illustration Section (2/3) -->
    <div class="w-2/3 bg-slate-900 hidden lg:flex relative overflow-hidden">
        <!-- Professional Background Shapes -->
        <div class="absolute inset-0">
            <!-- Large geometric shapes -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-blue-600/20 to-cyan-500/10 rounded-full -translate-y-48 translate-x-48"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-gradient-to-tr from-slate-700/30 to-blue-500/20 rounded-full translate-y-40 -translate-x-40"></div>

            <!-- Medium shapes -->
            <div class="absolute top-1/3 left-1/4 w-32 h-32 bg-blue-500/10 rotate-45 rounded-2xl floating-animation"></div>
            <div class="absolute bottom-1/3 right-1/4 w-24 h-24 bg-cyan-400/15 rounded-full floating-animation-delayed"></div>

            <!-- Small accent shapes -->
            <div class="absolute top-1/2 right-1/3 w-16 h-16 bg-white/5 rotate-12 rounded-lg floating-animation"></div>
            <div class="absolute top-1/4 right-1/2 w-12 h-12 bg-blue-400/20 rounded-full floating-animation-delayed"></div>

            <!-- Grid pattern overlay -->
            <div class="absolute inset-0 opacity-5">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>
        </div>

        <div class="flex flex-col justify-between h-full p-12 relative z-10">
            <!-- Logo -->
            <div>
                <img src="{{ asset('images/logo_boa.png') }}" alt="BOA Invest" class="h-16 brightness-0 invert">
            </div>

            <!-- Main Content -->
            <div class="flex flex-col items-start justify-center h-full text-white max-w-lg">
                <h1 class="text-5xl font-bold mb-6 leading-tight">
                    CRM<br>
                    <span class="text-transparent bg-gradient-to-r from-blue-400 to-cyan-300 bg-clip-text">
                            BOA Invest
                        </span>
                </h1>
                <p class="text-xl text-slate-300 mb-8 leading-relaxed">
                    Votre plateforme de gestion des relations investisseurs
                </p>

                <!-- Simple feature highlights -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-500 to-cyan-400 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-slate-300">Gestion centralisée des données</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-500 to-cyan-400 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-slate-300">Analyses et rapports avancés</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-500 to-cyan-400 flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <span class="text-slate-300">Suivi des interactions</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-sm text-slate-400">
                &copy; {{ date('Y') }} Bank of Africa BMCE Group
            </div>
        </div>
    </div>

    <!-- Login Form Section (1/3) -->
    <div class="w-full lg:w-1/3 flex items-center justify-center bg-gray-50/50">
        <div class="w-full max-w-md px-8 py-8">
            <!-- Mobile Logo -->
            <div class="block mb-8 text-center">
                <img src="{{ asset('images/logo_boa.png') }}" alt="Logo BOA Invest" class="h-16 mx-auto">
            </div>

            <!-- Welcome Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Connexion</h2>
                <p class="text-gray-600">Accédez à votre espace de gestion CRM</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl flex items-center">
                    <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}"
                  x-data="{ isLoading: false, showPassword: false }"
                  @submit="isLoading = true"
                  class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700">
                        Adresse Email
                    </label>
                    <div class="relative">
                        <input
                            id="email"
                            class="w-full h-12 pl-12 pr-4 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('email') border-red-400 focus:border-red-500 focus:ring-red-500/10 @enderror"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="prenom.nom@bankofafrica.com"
                        />
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    @error('email')
                    <p class="text-red-500 text-sm flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            class="w-full h-12 pl-12 pr-12 bg-white border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('password') border-red-400 focus:border-red-500 focus:ring-red-500/10 @enderror"
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••••••"
                        />
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <button
                            type="button"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                            @click="showPassword = !showPassword"
                        >
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-sm flex items-center mt-1">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button
                        type="submit"
                        class="w-full h-12 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center"
                        :class="{ 'opacity-75 cursor-not-allowed': isLoading }"
                        :disabled="isLoading"
                    >
                            <span x-show="!isLoading" class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                Se connecter
                            </span>
                        <span x-show="isLoading" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Connexion en cours...
                            </span>
                    </button>
                </div>

                <!-- Forgot Password Link -->
                @if (Route::has('password.request'))
                    <div class="text-center pt-4">
                        <a class="text-blue-600 hover:text-blue-700 text-sm font-medium inline-flex items-center transition-colors duration-200"
                           href="{{ route('password.request') }}">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mot de passe oublié ?
                        </a>
                    </div>
                @endif
            </form>

            <!-- Demo Credentials -->
            <div class="mt-8 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-amber-800 mb-2">Comptes de démonstration</h3>
                        <div class="text-xs text-amber-700 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">Administrateur:</span>
                                <span class="font-mono">admin@bankofafrica.com</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">Éditeur:</span>
                                <span class="font-mono">houda@bankofafrica.com</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-medium">Lecture seule:</span>
                                <span class="font-mono">ahmed@bankofafrica.com</span>
                            </div>
                            <div class="pt-1 border-t border-amber-200">
                                <span class="font-medium">Mot de passe:</span>
                                <span class="font-mono">password123</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile responsive adjustments -->
<style>
    @media (max-width: 1024px) {
        .w-2\/3 { display: none; }
        .w-full.lg\:w-1\/3 { width: 100%; }
    }

    .font-inter {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
</style>
</body>
</html>
