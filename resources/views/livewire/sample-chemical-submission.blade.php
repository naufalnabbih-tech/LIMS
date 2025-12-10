<div id="sample-chemical-submission-component">
    <div class="flex flex-col">
        <!-- Flash Messages Component -->
        <livewire:components.flash-messages />

        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Chemical Sample Submission</h2>
                    <p class="text-sm text-gray-600 mt-1">Submit and manage chemical samples for testing</p>
                </div>
                @permission('manage_samples')
                    <div>
                        <button wire:click="$dispatch('openCreateForm')"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 cursor-pointer">
                            {{-- <x-icon name="plus" class="w-4 h-4 mr-2"></x-icon> --}}
                            Submit Sample
                        </button>
                    </div>
                @endpermission
            </div>
        </div>

        <!-- Samples List -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200" style="overflow: visible;">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Submitted Samples</h3>
                        <p class="text-sm text-gray-600 mt-1">Track the status of submitted chemical samples</p>
                    </div>
                    <div class="w-64">
                        <div class="relative">
                            <input type="text"
                                wire:model.live.debounce.300ms="searchBatchLot"
                                placeholder="Search by Batch/Lot..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            @if($searchBatchLot)
                                <button wire:click="$set('searchBatchLot', '')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto" style="overflow-y: visible; overflow-x: auto;">
                <table class="min-w-full divide-y divide-gray-200" style="position: relative;">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sample Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Batch / Lot </th>
                            <th wire:click="sortByColumn('submission_time')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-1">
                                    <span>Submission</span>
                                    @if ($sortBy === 'submission_time')
                                        @if ($sortDirection === 'asc')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th wire:click="sortByColumn('status')"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-1">
                                    <span>Status</span>
                                    @if ($sortBy === 'status')
                                        @if ($sortDirection === 'asc')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" />
                                            </svg>
                                        @endif
                                    @else
                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($samples as $sample)
                            @include('livewire.sample-chemical-submission.components.sample-table-row', [
                                'sample' => $sample,
                            ])
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <livewire:components.empty-state title="No samples submitted"
                                        description="Start by submitting your first chemical sample"
                                        buttonText="Submit Sample" buttonEvent="openCreateForm" icon="document" />
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
    @include('livewire.sample-chemical-submission.components.coa-form')

</div>

@push('scripts')
    <script src="{{ asset('js/sample-label-printer.js') }}"></script>
@endpush
