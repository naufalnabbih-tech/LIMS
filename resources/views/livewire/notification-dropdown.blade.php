<div class="relative" x-data="{ open: false }">
    <button type="button"
            @click="open = !open"
            class="p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 cursor-pointer">
        <span class="sr-only">View notifications</span>
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9.09c0-2.38-1.34-4.46-3.37-5.37A1.996 1.996 0 0010 2a1.996 1.996 0 00-1.63 1.72C6.34 4.63 5 6.71 5 9.09V12l-5 5h20z"/>
        </svg>
        <!-- Notification badge -->
        @if($totalNotifications > 0)
        <span class="absolute -top-0.5 -right-0.5 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-medium">
            {{ $totalNotifications > 99 ? '99+' : $totalNotifications }}
        </span>
        @endif
    </button>

    <!-- Notifications Modal -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] overflow-y-auto"
         style="display: none;">

        <!-- Background overlay -->
        <div @click="open = false" class="fixed inset-0 transition-opacity cursor-pointer"></div>

        <!-- Modal container - positioned top-right -->
        <div class="flex justify-end items-start min-h-screen pt-16 pr-4 pointer-events-none">
            <!-- Modal panel -->
            <div class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all w-full max-w-md pointer-events-auto">

                <!-- Header -->
                <div class="bg-white px-4 pt-5 pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Notifications
                        </h3>
                        <div class="flex items-center space-x-2">
                            @if($totalNotifications > 0)
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                                {{ $totalNotifications }}
                            </span>
                            @endif
                            <button @click="open = false"
                                    class="p-1.5 hover:bg-gray-100 rounded-md transition-colors cursor-pointer">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Notifications list -->
                <div class="max-h-96 overflow-y-auto bg-gray-50">
                    @if($totalNotifications > 0)

                        <!-- Pending Handovers (Orange - Action Required) -->
                        @if($pendingHandovers->count() > 0)
                            <div class="mb-2">
                                <div class="px-4 py-2 bg-orange-100">
                                    <p class="text-xs font-semibold text-orange-800 uppercase tracking-wide">
                                        Samples Waiting for You ({{ $pendingHandovers->count() }})
                                    </p>
                                </div>
                                @foreach($pendingHandovers as $handover)
                                <div class="bg-white mx-2 my-2 p-3 rounded-lg border border-orange-200 hover:border-orange-300 hover:shadow-sm transition-all cursor-pointer"
                                     wire:click="openTakeOverModal({{ $handover->id }})">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 bg-orange-500 rounded-full flex items-center justify-center">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900">
                                                Hand Over Request
                                            </p>
                                            <p class="text-sm text-gray-700 mt-1">
                                                <span class="font-semibold">{{ $handover->sample->material->name ?? 'N/A' }}</span>
                                                <span class="text-gray-500"> - Batch: {{ $handover->sample->batch_lot }}</span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                From: {{ $handover->fromAnalyst->name ?? 'N/A' }}
                                            </p>
                                            @if($handover->reason)
                                            <p class="text-xs text-gray-600 italic mt-1">
                                                "{{ Str::limit($handover->reason, 60) }}"
                                            </p>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $handover->submitted_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="ml-2">
                                            <svg class="h-5 w-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- My Handovers (Blue - Info) -->
                        @if($myHandovers->count() > 0)
                            <div>
                                <div class="px-4 py-2 bg-blue-100">
                                    <p class="text-xs font-semibold text-blue-800 uppercase tracking-wide">
                                        Your Hand Over Requests ({{ $myHandovers->count() }})
                                    </p>
                                </div>
                                @foreach($myHandovers as $handover)
                                <div class="bg-white mx-2 my-2 p-3 rounded-lg border border-blue-200 hover:border-blue-300 hover:shadow-sm transition-all">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium text-gray-900">
                                                Waiting to be Taken
                                            </p>
                                            <p class="text-sm text-gray-700 mt-1">
                                                <span class="font-semibold">{{ $handover->sample->material->name ?? 'N/A' }}</span>
                                                <span class="text-gray-500"> - Batch: {{ $handover->sample->batch_lot }}</span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Status: <span class="text-blue-600 font-medium">Pending</span>
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                Submitted {{ $handover->submitted_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif

                    @else
                        <!-- Empty state -->
                        <div class="p-8 text-center bg-white mx-2 my-2 rounded-lg">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-900">No notifications</p>
                            <p class="text-xs text-gray-500 mt-1">You're all caught up!</p>
                        </div>
                    @endif
                </div>

                {{-- <!-- Footer -->
                @if($totalNotifications > 0)
                <div class="bg-gray-50 px-4 py-3 flex justify-center">
                    <button wire:click="loadNotifications"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Refresh
                    </button>
                </div>
                @endif --}}
            </div>
        </div>
    </div>
</div>
