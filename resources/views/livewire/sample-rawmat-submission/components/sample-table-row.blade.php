@php
    // Calculate status name
    $statusName = $sample->status
        ? $sample->status->name
        : 'pending';

    // Get active handover info for permission checking
    $activeHandover = $sample->handovers()->where('status','pending')->first();
    $handoverFromAnalystId = $activeHandover ? $activeHandover->from_analyst_id : null;
@endphp

<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900">
            {{ $sample->material->name ?? 'N/A' }}</div>
        <div class="text-sm text-gray-500">{{ $sample->category->name ?? 'N/A' }}</div>
        <div class="text-xs text-gray-400">Batch: {{ $sample->batch_lot }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">{{ $sample->supplier }}</div>
        <div class="text-xs text-gray-500">{{ $sample->vehicle_container_number }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">
            {{ $sample->submission_time->format('M d, Y') }}</div>
        <div class="text-xs text-gray-500">{{ $sample->submission_time->format('H:i') }}
        </div>
        <div class="text-xs text-gray-400">by {{ $sample->submittedBy->name ?? 'N/A' }}
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <span
            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $sample->status_color }}">
            {{ $sample->status_label }}
        </span>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
        @if ($sample->has_coa)
            <div class="flex flex-col space-y-1">
                <span class="inline-flex items-center text-green-600">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                    Yes
                </span>
                @if ($sample->coa_file_path)
                    <a href="{{ Storage::url($sample->coa_file_path) }}" target="_blank"
                        class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 underline">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Download
                    </a>
                @endif
            </div>
        @else
            <span class="inline-flex items-center text-red-600">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
                No
            </span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <!-- Sample Action Button -->
        <button
            @click="
            if (window.globalDropdown && typeof window.globalDropdown.open === 'function') {
                window.globalDropdown.open({{ $sample->id }}, {
                    batch: @js($sample->batch_lot ?? 'N/A'),
                    status: @js($statusName),
                    supplier: @js($sample->supplier ?? 'N/A'),
                    material: @js($sample->material->name ?? 'N/A'),
                    handoverFromAnalystId: @js($handoverFromAnalystId),
                    currentUserId: @js(auth()->id()),
                    userCanEdit: @js($userPermissions['canEdit'] ?? false),
                    userCanAnalyze: @js($userPermissions['canAnalyze'] ?? false),
                    userCanReview: @js($userPermissions['canReview'] ?? false),
                    userCanApprove: @js($userPermissions['canApprove'] ?? false),
                    userCanDelete: @js($userPermissions['canDelete'] ?? false),
                    userCanCreateCoA: @js($userPermissions['canCreateCoA'] ?? false),
                    buttonRect: $el.getBoundingClientRect()
                });
            } else {
                alert('Dropdown not ready. Please refresh the page.');
            }
        "
            type="button"
            class="inline-flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 hover:text-gray-800 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500 transition-all duration-150 cursor-pointer shadow-sm hover:shadow-md"
            aria-label="Open sample actions">
            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
            </svg>
            <span class="font-medium">Actions</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </td>
</tr>
