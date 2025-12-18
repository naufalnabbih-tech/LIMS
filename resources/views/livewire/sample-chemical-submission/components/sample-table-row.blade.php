@php
    // Calculate status name
    $statusName = $sample->statusRelation
        ? $sample->statusRelation->name
        : ($sample->status ?? 'submitted');

    // Get active handover info for permission checking
    $activeHandover = $sample->handovers()->where('status','pending')->first();
    $handoverFromAnalystId = $activeHandover ? $activeHandover->from_analyst_id : null;
@endphp

<tr class="hover:bg-gray-50">
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm font-medium text-gray-900">
            {{ $sample->material->name ?? 'N/A' }}</div>
        <div class="text-sm text-gray-500">{{ $sample->category->name ?? 'N/A' }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">
            {{ $sample->batch_lot ?? 'N/A' }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900"></div>
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

    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <!-- Sample Action Button -->
        <button
            @click="
            if (window.globalDropdown && typeof window.globalDropdown.open === 'function') {
                window.globalDropdown.open({{ $sample->id }}, {
                    batch: @js($sample->batch_lot ?? 'N/A'),
                    material: @js($sample->material->name ?? 'N/A'),
                    status: @js($statusName),
                    handoverFromAnalystId: @js($handoverFromAnalystId),
                    currentUserId: @js(auth()->id()),
                    userCanEdit: @js($userPermissions['canEdit'] ?? false),
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
