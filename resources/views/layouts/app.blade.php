<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="boa-corporate">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'CRM Investisseurs') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-50" x-data="{ sidebarOpen: true, sidebarCollapsed: false }">
<div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 transition-all duration-300 ease-in-out"
        :class="{
                'w-64': sidebarOpen && !sidebarCollapsed,
                'w-16': sidebarOpen && sidebarCollapsed,
                '-translate-x-full': !sidebarOpen
            }"
    >
        @include('layouts.sidebar')
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col"
         :class="{
                'lg:ml-64': sidebarOpen && !sidebarCollapsed,
                'lg:ml-16': sidebarOpen && sidebarCollapsed,
                'lg:ml-0': !sidebarOpen
             }">

        <!-- Header -->
        @include('layouts.header')

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mx-4 mt-4 alert alert-success animate-fade-in-scale" x-data="{ show: true }" x-show="show">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="btn btn-sm btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 mt-4 alert alert-error animate-fade-in-scale" x-data="{ show: true }" x-show="show">
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="btn btn-sm btn-ghost">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <!-- Page Header -->
            @hasSection('page-header')
                <div class="bg-white shadow-sm border-b border-slate-200">
                    <div class="px-4 sm:px-6 lg:px-8 py-6">
                        @yield('page-header')
                    </div>
                </div>
            @endif

            <!-- Main Page Content -->
            <div class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        @include('layouts.footer')
    </div>
</div>

<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-slate-900 bg-opacity-50 lg:hidden"
     @click="sidebarOpen = false"
     style="display: none;"
></div>

@stack('scripts')
</body>
</html>
