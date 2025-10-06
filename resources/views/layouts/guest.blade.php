<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LIMS') }} - Sign In</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

</head>
<body class="min-h-screen bg-gradient flex items-center justify-center p-4">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-gradient opacity-90"></div>
    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <!-- Login Card -->
    <div class="relative w-full max-w-md fade-in">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-lg mb-4 float-animation logo-pulse">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2 slide-up">{{ config('app.name', 'LIMS') }}</h1>
            <p class="text-indigo-100 slide-up" style="animation-delay: 0.1s;">Laboratory Information Management System</p>
        </div>

        <!-- Login Form Card -->
        <div class="bg-white/95 backdrop-blur-sm rounded-2xl card-shadow p-8 slide-up" style="animation-delay: 0.2s;">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 slide-up" style="animation-delay: 0.3s;">
            <p class="text-indigo-100 text-sm">
                © {{ date('Y') }} {{ config('app.name', 'LIMS') }}. All rights reserved.
            </p>
        </div>
    </div>

    @livewireScripts
</body>
</html>
