<div id="sample-chemical-submission-component">
    <div class="flex flex-col">
        <!-- Flash Messages Component -->
        <livewire:components.flash-messages />

        <!-- Pending Handovers Alert (Orange) -->
        @if($pendingHandovers && $pendingHandovers->count() > 0)
        <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-4 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-orange-800">
                        Sample Handover Menunggu Anda ({{ $pendingHandovers->count() }})
                    </h3>
                    <div class="mt-2 text-sm text-orange-700">
                        <ul class="list-disc pl-5 space-y-2">
                            @foreach($pendingHandovers as $handover)
                            <li class="py-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="font-semibold">{{ $handover->sample->material->name ?? 'N/A' }}</span>
                                        - Batch: {{ $handover->sample->batch_lot }}
                                        <br>
                                        <span class="text-xs">Dari: {{ $handover->fromAnalyst->name ?? 'N/A' }} |
                                        Waktu: {{ $handover->submitted_at->format('d M Y, H:i') }}</span>
                                        @if($handover->reason)
                                        <br>
                                        <span class="text-xs italic">Alasan: {{ $handover->reason }}</span>
                                        @endif
                                    </div>
                                    <button wire:click="takeSample({{ $handover->id }})"
                                            class="ml-4 px-3 py-1 bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium rounded transition-colors">
                                        Ambil Sample
                                    </button>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- My Handovers Info (Blue) -->
        @if($myHandovers && $myHandovers->count() > 0)
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded-lg">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium text-blue-800">
                        Sample yang Anda Hand Over ({{ $myHandovers->count() }})
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($myHandovers as $handover)
                            <li>
                                <span class="font-semibold">{{ $handover->sample->material->name ?? 'N/A' }}</span>
                                - Batch: {{ $handover->sample->batch_lot }}
                                <br>
                                <span class="text-xs">Diberikan ke: {{ $handover->toAnalyst->name ?? 'N/A' }} pada {{ $handover->submitted_at->format('d M Y, H:i') }}</span>
                                <br>
                                <span class="text-xs italic">⏳ Menunggu diambil...</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Chemical Sample Submission</h2>
                    <p class="text-sm text-gray-600 mt-1">Submit and manage chemical samples for testing</p>
                </div>
                <div>
                    <button wire:click="$dispatch('openCreateForm')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
                        {{-- <x-icon name="plus" class="w-4 h-4 mr-2"></x-icon> --}}
                        Submit Sample
                    </button>
                </div>
            </div>
        </div>

        <!-- Samples List -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200" style="overflow: visible;">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Submitted Samples</h3>
                <p class="text-sm text-gray-600 mt-1">Track the status of submitted chemical samples</p>
            </div>

            <div class="overflow-x-auto" style="overflow-y: visible; overflow-x: auto;">
                <table class="min-w-full divide-y divide-gray-200" style="position: relative;">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sample Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Supplier Info</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Submission</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                CoA</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($samples as $sample)
                            @include('livewire.sample-chemical-submission.components.sample-table-row', ['sample' => $sample])
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <livewire:components.empty-state
                                        title="No samples submitted"
                                        description="Start by submitting your first chemical sample"
                                        buttonText="Submit Sample"
                                        buttonEvent="openCreateForm"
                                        icon="document"
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($samples->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 rounded-b-xl">
                    {{ $samples->links() }}
                </div>
            @endif
        </div>
    </div>



    <!-- Child Components -->
    <livewire:sample-chemical-submission.components.sample-actions-dropdown />
    <livewire:sample-chemical-submission.components.create-sample-form />
    <livewire:sample-chemical-submission.components.analysis-form />
    <livewire:sample-chemical-submission.components.sample-details />
    <livewire:sample-chemical-submission.components.edit-sample-form />
    <livewire:sample-chemical-submission.components.hand-over-form />
    <livewire:sample-chemical-submission.components.take-over-form />

</div>

@push('scripts')
<script src="{{ asset('js/sample-label-printer.js') }}"></script>
<script>
// Ensure globalDropdown is available even if component hasn't initialized yet
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for Livewire components to initialize
    setTimeout(() => {
        if (!window.globalDropdown) {
            console.warn('Global dropdown not initialized. Waiting for component...');
        }
    }, 100);
});
</script>
@endpush
