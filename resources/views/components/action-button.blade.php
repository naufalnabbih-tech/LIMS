@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'disabled' => false,
    'type' => 'button'
])

@php
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
        'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 text-white focus:ring-yellow-500',
        'info' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500',
        'outline' => 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 focus:ring-gray-200',
        'ghost' => 'bg-transparent hover:bg-gray-100 text-gray-700 focus:ring-gray-200'
    ];
    
    $sizes = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg'
    ];
    
    $iconSizes = [
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
        'xl' => 'w-6 h-6'
    ];
    
    $baseClasses = 'inline-flex items-center font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 cursor-pointer';
    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $sizeClasses = $sizes[$size] ?? $sizes['md'];
    $iconSizeClasses = $iconSizes[$size] ?? $iconSizes['md'];
    
    $classes = $baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses;
    
    if ($disabled || $loading) {
        $classes .= ' opacity-50 cursor-not-allowed';
    }
@endphp

<button 
    type="{{ $type }}"
    @if($disabled || $loading) disabled @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    @if($loading)
        <svg class="animate-spin -ml-1 {{ $iconPosition === 'right' ? 'ml-2 mr-0' : 'mr-2' }} {{ $iconSizeClasses }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($icon && $iconPosition === 'left')
        <span class="mr-2 {{ $iconSizeClasses }}">
            {!! $icon !!}
        </span>
    @endif
    
    <span @if($loading) wire:loading.remove @endif>
        {{ $slot }}
    </span>
    
    @if($loading)
        <span wire:loading class="ml-1">Loading...</span>
    @endif
    
    @if($icon && $iconPosition === 'right' && !$loading)
        <span class="ml-2 {{ $iconSizeClasses }}">
            {!! $icon !!}
        </span>
    @endif
</button>