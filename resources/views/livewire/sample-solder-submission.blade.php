<div>
    <div class="flex flex-col">
        <!-- Flash Messages Component -->
        <livewire:components.flash-messages />

        <!-- Header Section -->
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 mb-4">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Raw Material Sample Submission</h2>
                    <p class="text-sm text-gray-600 mt-1">Submit and manage raw material samples for testing</p>
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
                <p class="text-sm text-gray-600 mt-1">Track the status of submitted raw material samples</p>
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
                            @include('livewire.sample-rawmat-submission.components.sample-table-row', ['sample' => $sample])
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <livewire:components.empty-state
                                        title="No samples submitted"
                                        description="Start by submitting your first raw material sample"
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
    <livewire:sample-rawmat-submission.components.sample-actions-dropdown />
    <livewire:sample-rawmat-submission.components.create-sample-form />
    <livewire:sample-rawmat-submission.components.analysis-form />
    <livewire:sample-rawmat-submission.components.sample-details />
    <livewire:sample-rawmat-submission.components.edit-sample-form />
    <livewire:sample-rawmat-submission.components.hand-over-form />
    <livewire:sample-rawmat-submission.components.take-over-form />

</div>

@push('scripts')
<script src="{{ asset('js/sample-label-printer.js') }}"></script>
<script src="{{ asset('js/sample-dropdown.js') }}"></script>
@endpush
