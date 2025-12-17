@props(['label' => '', 'items' => [], 'modelName' => '', 'required' => false, 'disabled' => false, 'placeholder' => 'Select an option', 'selectedValue' => null])

<div class="relative" x-data="{
    open: false,
    toggle() {
        if (!{{ $disabled ? 'true' : 'false' }}) {
            this.open = !this.open
        }
    },
    close() { this.open = false }
}" @click.away="close()">

    @if ($label)
        <label class="block text-sm font-bold mb-2 text-slate-700">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <button type="button" @click="toggle()"
            class="relative w-full py-3 px-4 text-left border rounded-lg shadow-sm focus:outline-none transition-all duration-200
            @error($modelName) border-red-500 focus:ring-red-200 @else border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 @enderror
            {{ $disabled ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 cursor-pointer' }}">
            <span class="block truncate">
                @if ($disabled && $items->isEmpty())
                    No options available
                @elseif($selectedValue)
                    {{ $items->firstWhere('id', $selectedValue)?->name ?? $placeholder }}
                @else
                    <span class="text-gray-400">{{ $placeholder }}</span>
                @endif
            </span>

            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200"
                    :class="open ? 'transform rotate-180' : ''" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7"></path>
                </svg>
            </span>
        </button>

        <div x-show="open" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
            style="display: none;"
            class="absolute z-50 w-full mt-2 bg-white border border-gray-100 rounded-lg shadow-xl max-h-60 overflow-y-auto custom-scrollbar">

            <ul class="py-1 text-base text-gray-700">
                @forelse ($items as $item)
                    <li wire:key="dropdown-item-{{ $modelName }}-{{ $item->id }}">
                        <button type="button"
                            wire:click="$set('{{ $modelName }}', {{ $item->id }})"
                            @click="close()"
                            class="group flex items-center justify-between w-full px-4 py-3 text-sm text-left hover:bg-gray-50 hover:text-gray-700 transition-colors cursor-pointer
                            {{ $selectedValue == $item->id ? 'bg-indigo-50 text-gray-700 font-semibold' : '' }}">

                            <span>{{ $item->name }}</span>

                            @if ($selectedValue == $item->id)
                                <svg class="w-4 h-4 text-gray-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            @endif
                        </button>
                    </li>
                @empty
                    <li>
                        <div class="px-4 py-3 text-sm text-gray-500 text-center">
                            No options available
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    @error($modelName)
        <p class="text-red-500 text-sm mt-1 animate-pulse">{{ $message }}</p>
    @enderror
</div>
