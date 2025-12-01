<div>
    <!-- Toast Container - Fixed to top right -->
    <div class="fixed top-20 right-4 z-[9999] space-y-3 pointer-events-none"
         x-data="{
             removeToastAfterDelay(toastId, delay) {
                 setTimeout(() => {
                     @this.call('removeToast', toastId);
                 }, delay);
             }
         }"
         @remove-toast-after-delay.window="removeToastAfterDelay($event.detail.toastId, $event.detail.delay)">

        @foreach($toasts as $toast)
            <div x-data="{ show: false }"
                 x-init="setTimeout(() => show = true, 100)"
                 x-show="show"
                 x-transition:enter="transform transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transform transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0 opacity-100"
                 x-transition:leave-end="translate-x-full opacity-0"
                 class="pointer-events-auto w-96 bg-white rounded-lg shadow-2xl ring-1 ring-black ring-opacity-5 overflow-hidden">

                @if($toast['type'] === 'pending')
                    <!-- Pending Handover Toast (Orange - Action Required) -->
                    <div class="p-4 border-l-4 border-orange-500 bg-gradient-to-r from-orange-50 to-white">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 bg-orange-500 rounded-full flex items-center justify-center animate-pulse">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-semibold text-orange-900">
                                    🔔 New Hand Over Request!
                                </p>
                                <p class="text-sm text-gray-700 mt-1">
                                    {{ $toast['message'] }}
                                </p>
                                @if(!empty($toast['details']))
                                    <div class="mt-2 text-xs text-gray-600">
                                        @if(isset($toast['details']['material']))
                                            <p><span class="font-semibold">Material:</span> {{ $toast['details']['material'] }}</p>
                                        @endif
                                        @if(isset($toast['details']['batch']))
                                            <p><span class="font-semibold">Batch:</span> {{ $toast['details']['batch'] }}</p>
                                        @endif
                                        @if(isset($toast['details']['from']))
                                            <p><span class="font-semibold">From:</span> {{ $toast['details']['from'] }}</p>
                                        @endif
                                    </div>
                                @endif
                                <div class="mt-2 flex items-center text-xs text-orange-700">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    Check the notification bell to take action
                                </div>
                            </div>
                            <button @click="@this.call('removeToast', '{{ $toast['id'] }}')"
                                    class="ml-2 flex-shrink-0 text-orange-400 hover:text-orange-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @elseif($toast['type'] === 'submitted')
                    <!-- Submitted Handover Toast (Blue - Info) -->
                    <div class="p-4 border-l-4 border-blue-500 bg-gradient-to-r from-blue-50 to-white">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-semibold text-blue-900">
                                    ✓ Hand Over Submitted
                                </p>
                                <p class="text-sm text-gray-700 mt-1">
                                    {{ $toast['message'] }}
                                </p>
                                @if(!empty($toast['details']))
                                    <div class="mt-2 text-xs text-gray-600">
                                        @if(isset($toast['details']['material']))
                                            <p><span class="font-semibold">Material:</span> {{ $toast['details']['material'] }}</p>
                                        @endif
                                        @if(isset($toast['details']['batch']))
                                            <p><span class="font-semibold">Batch:</span> {{ $toast['details']['batch'] }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <button @click="@this.call('removeToast', '{{ $toast['id'] }}')"
                                    class="ml-2 flex-shrink-0 text-blue-400 hover:text-blue-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @elseif($toast['type'] === 'taken')
                    <!-- Taken Over Toast (Green - Success) -->
                    <div class="p-4 border-l-4 border-green-500 bg-gradient-to-r from-green-50 to-white">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-semibold text-green-900">
                                    ✓ Sample Taken Over
                                </p>
                                <p class="text-sm text-gray-700 mt-1">
                                    {{ $toast['message'] }}
                                </p>
                                @if(!empty($toast['details']))
                                    <div class="mt-2 text-xs text-gray-600">
                                        @if(isset($toast['details']['material']))
                                            <p><span class="font-semibold">Material:</span> {{ $toast['details']['material'] }}</p>
                                        @endif
                                        @if(isset($toast['details']['batch']))
                                            <p><span class="font-semibold">Batch:</span> {{ $toast['details']['batch'] }}</p>
                                        @endif
                                        @if(isset($toast['details']['by']))
                                            <p><span class="font-semibold">Taken by:</span> {{ $toast['details']['by'] }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <button @click="@this.call('removeToast', '{{ $toast['id'] }}')"
                                    class="ml-2 flex-shrink-0 text-green-400 hover:text-green-600 transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
