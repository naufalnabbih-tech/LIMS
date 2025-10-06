@props([
    'show' => false,
    'title',
    'subtitle' => null,
    'icon' => null,
    'iconColor' => 'blue',
    'maxWidth' => '2xl',
    'closeable' => true,
    'wireClick' => null
])

@php
    $maxWidthClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl'
    ];
    
    $iconColorClasses = [
        'blue' => 'from-blue-600 to-indigo-600',
        'green' => 'from-green-600 to-emerald-600',
        'purple' => 'from-purple-600 to-violet-600',
        'orange' => 'from-orange-600 to-amber-600',
        'red' => 'from-red-600 to-rose-600'
    ];
@endphp

@if($show)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50"
        x-data="{ show: true }" 
        x-show="show" 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" 
        @if($closeable && $wireClick) @click.self="{{ $wireClick }}" @endif>

        <div class="relative top-4 mx-auto my-4 w-11/12 md:w-4/5 lg:w-3/5 xl:w-1/2 {{ $maxWidthClasses[$maxWidth] }} shadow-2xl rounded-2xl bg-white overflow-hidden max-h-[calc(100vh-2rem)]"
            x-transition:enter="ease-out duration-300" 
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100" 
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95">

            <!-- Modal Header -->
            <div class="bg-gradient-to-r {{ $iconColorClasses[$iconColor] }} px-6 py-5 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        @if($icon)
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                {!! $icon !!}
                            </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-semibold text-white">{{ $title }}</h3>
                            @if($subtitle)
                                <p class="text-blue-100 text-sm">{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>
                    @if($closeable && $wireClick)
                        <button type="button" wire:click="{{ str_replace('wire:click=', '', $wireClick) }}"
                            class="text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm p-2 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Modal Content -->
            <div class="overflow-y-auto flex-1 max-h-[calc(100vh-10rem)]">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif