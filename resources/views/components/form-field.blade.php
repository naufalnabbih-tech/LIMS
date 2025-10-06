@props([
    'label',
    'name',
    'type' => 'text',
    'required' => false,
    'icon' => null,
    'placeholder' => null,
    'options' => [],
    'wireModel' => null,
    'disabled' => false,
    'readonly' => false,
    'accept' => null,
    'colSpan' => 1,
    'help' => null
])

@php
    $wireModelAttribute = $wireModel ?? "wire:model=\"{$name}\"";
    $colSpanClass = $colSpan > 1 ? "md:col-span-{$colSpan}" : '';
    $inputClasses = "w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all duration-200";
    $inputClasses .= $icon ? ' pl-10' : '';
    $inputClasses .= " @error('{$name}') border-red-500 ring-red-200 @enderror";
    
    if ($disabled) {
        $inputClasses .= ' bg-gray-100 cursor-not-allowed';
    }
    if ($readonly) {
        $inputClasses .= ' bg-gray-100 text-gray-600 cursor-not-allowed';
    }
    if ($type === 'file') {
        $inputClasses = "w-full px-4 py-3 bg-white border-2 border-dashed border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('{$name}') border-red-500 @enderror file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all duration-200";
    }
@endphp

<div class="space-y-2 {{ $colSpanClass }}">
    <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700">
        <span class="flex items-center space-x-1">
            <span>{{ $label }}</span>
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </span>
        @if($help)
            <span class="text-xs text-gray-500 font-normal block mt-1">{{ $help }}</span>
        @endif
    </label>
    
    <div class="relative">
        @if($type === 'select')
            <select 
                {!! $wireModelAttribute !!}
                id="{{ $name }}" 
                name="{{ $name }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                class="{{ $inputClasses }}"
            >
                <option value="">{{ $placeholder ?: "Choose {$label}" }}</option>
                @if(is_array($options))
                    @foreach($options as $value => $text)
                        <option value="{{ $value }}">{{ $text }}</option>
                    @endforeach
                @else
                    {{ $options }}
                @endif
            </select>
        
        @elseif($type === 'textarea')
            <textarea 
                {!! $wireModelAttribute !!}
                id="{{ $name }}" 
                name="{{ $name }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
                class="{{ $inputClasses }}"
                placeholder="{{ $placeholder }}"
                rows="4"
            ></textarea>
        
        @elseif($type === 'checkbox')
            <div class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                <div class="flex items-center h-5">
                    <input 
                        type="checkbox" 
                        {!! $wireModelAttribute !!}
                        id="{{ $name }}" 
                        name="{{ $name }}"
                        @if($disabled) disabled @endif
                        class="w-5 h-5 text-blue-600 bg-white border-2 border-blue-300 rounded focus:ring-blue-500 focus:ring-2 transition-all duration-200"
                    >
                </div>
                <div class="ml-3">
                    <label for="{{ $name }}" class="text-sm font-semibold text-blue-900 cursor-pointer">
                        {{ $label }}
                    </label>
                    @if($help)
                        <p class="text-xs text-blue-700 mt-1">{{ $help }}</p>
                    @endif
                </div>
            </div>
        
        @else
            <input 
                type="{{ $type }}" 
                {!! $wireModelAttribute !!}
                id="{{ $name }}" 
                name="{{ $name }}"
                @if($required) required @endif
                @if($disabled) disabled @endif
                @if($readonly) readonly @endif
                @if($accept) accept="{{ $accept }}" @endif
                class="{{ $inputClasses }}"
                placeholder="{{ $placeholder }}"
            >
        @endif
        
        @if($icon && $type !== 'checkbox' && $type !== 'file')
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                {!! $icon !!}
            </div>
        @endif
    </div>
    
    @error($name)
        <p class="text-red-500 text-xs mt-1 flex items-center space-x-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ $message }}</span>
        </p>
    @enderror
    
    @if($readonly && $type === 'time')
        <p class="text-xs text-gray-500 flex items-center space-x-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            <span>Time will be set to current time when submitted</span>
        </p>
    @endif
</div>