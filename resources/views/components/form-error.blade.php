@props(['type' => 'validation'])

@php
    $typeClasses = [
        'validation' => 'bg-red-50 border-red-200 text-red-800',
        'session' => 'bg-red-50 border-red-200 text-red-800',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800'
    ];
@endphp

@if($type === 'validation' && $errors->any())
    <div class="mb-6 mx-6 p-4 {{ $typeClasses[$type] }} border rounded-xl">
        <div class="flex items-center space-x-2 mb-2">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <h4 class="text-sm font-medium">Please correct the following errors:</h4>
        </div>
        <ul class="text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>

@elseif($type === 'session' && session()->has('error'))
    <div class="mb-6 mx-6 p-4 {{ $typeClasses[$type] }} border rounded-xl">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    </div>

@elseif(isset($slot) && !empty(trim($slot)))
    <div class="mb-6 mx-6 p-4 {{ $typeClasses[$type] ?? $typeClasses['validation'] }} border rounded-xl">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div class="text-sm">{{ $slot }}</div>
        </div>
    </div>
@endif