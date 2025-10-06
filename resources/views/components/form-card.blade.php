@props(['title', 'subtitle' => null, 'icon' => null, 'iconColor' => 'blue'])

@php
    $iconColorClasses = [
        'blue' => 'bg-blue-100 text-blue-600',
        'green' => 'bg-green-100 text-green-600',
        'purple' => 'bg-purple-100 text-purple-600',
        'orange' => 'bg-orange-100 text-orange-600',
        'red' => 'bg-red-100 text-red-600'
    ];
@endphp

<div {{ $attributes->merge(['class' => 'mb-8']) }}>
    <div class="flex items-center space-x-2 mb-6">
        @if($icon)
            <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $iconColorClasses[$iconColor] }}">
                {!! $icon !!}
            </div>
        @endif
        <div>
            <h4 class="text-lg font-semibold text-gray-900">{{ $title }}</h4>
            @if($subtitle)
                <p class="text-sm text-gray-500">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{ $slot }}
    </div>
</div>