<div>
    <!-- Sample Action Dropdown -->
    <div x-data="sampleDropdown()" x-init="initGlobalDropdown()" @click.away="handleClickAway()"
        @keydown.escape.window="closeDropdown()">

        <!-- Dropdown Content -->
        <div x-show="isOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed z-[9999] w-80 rounded-xl bg-white shadow-2xl ring-1 ring-black/5 focus:outline-none border border-gray-200"
            :style="{
                top: (sampleData.position?.top || 200) + 'px',
                left: (sampleData.position?.left || 300) + 'px',
                maxHeight: '85vh',
                overflowY: 'auto'
            }"
            x-cloak>

            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900">Sample Actions</h3>
                        <p class="text-xs text-blue-600 mt-0.5">
                            <span x-text="sampleData.material || 'N/A'"></span> •
                            <span x-text="'Batch: ' + (sampleData.batch || 'N/A')"></span>
                        </p>
                    </div>
                    <button @click="closeDropdown()"
                        class="p-1.5 hover:bg-white/60 rounded-md transition-colors cursor-pointer">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Scrollable Actions Container -->
            <div class="max-h-96 overflow-y-auto custom-scrollbar">

                <!-- View & Info Actions -->
                <div class="p-3">
                    <div class="px-2 py-1.5">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">View & Information
                        </h4>
                    </div>
                    <div class="space-y-1">
                        <button @click="callLivewireMethod('viewDetails', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">View Details</span>
                                <span class="text-xs text-gray-500">View complete sample information</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- Edit Actions -->
                <div class="p-3" x-show="sampleData.canEdit">
                    <div class="px-2 py-1.5">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Modify</h4>
                    </div>
                    <div class="space-y-1">
                        <button @click="callLivewireMethod('editSample', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-amber-100 group-hover:bg-amber-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Edit Sample</span>
                                <span class="text-xs text-gray-500">Modify sample details and information</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Process Actions -->
                <div class="p-3" x-show="sampleData.canStartAnalysis || sampleData.canContinueAnalysis || sampleData.canHandOver || sampleData.canTakeOver">
                    <div class="px-2 py-1.5">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Process Management
                        </h4>
                    </div>
                    <div class="space-y-1">
                        <button x-show="sampleData.canStartAnalysis"
                            @click="callLivewireMethod('openAnalysisForm', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Start Analysis</span>
                                <span class="text-xs text-gray-500">Begin laboratory analysis process</span>
                            </div>
                        </button>

                        <button x-show="sampleData.canContinueAnalysis"
                            @click="callLivewireMethod('continueAnalysis', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Continue Analysis</span>
                                <span class="text-xs text-gray-500">Go to analysis laboratory page</span>
                            </div>
                        </button>

                        <!-- Submit to Hand Over Button -->
                        <button x-show="sampleData.canHandOver"
                            @click="callLivewireMethod('openHandOverForm', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-yellow-100 group-hover:bg-yellow-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Submit to Hand Over</span>
                                <span class="text-xs text-gray-500">Transfer sample to another analyst</span>
                            </div>
                        </button>

                        <!-- Take Over Button -->
                        <button x-show="sampleData.canTakeOver"
                            @click="callLivewireMethod('openTakeOverForm', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-indigo-100 group-hover:bg-indigo-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Take Over Sample</span>
                                <span class="text-xs text-gray-500">Accept and continue this sample analysis</span>
                            </div>
                        </button>

                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100" x-show="sampleData.canStartAnalysis || sampleData.canContinueAnalysis || sampleData.canHandOver || sampleData.canTakeOver"></div>

                <!-- Review & Approval Actions -->
                <div class="p-3">
                    <div class="px-2 py-1.5" x-show="['analysis_completed', 'review', 'reviewed', 'approved'].includes(sampleData.status)">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Review & Approval</h4>
                    </div>
                    <div class="space-y-1">
                        <button x-show="['analysis_completed', 'review', 'reviewed', 'approved'].includes(sampleData.status)"
                            @click="
                                callLivewireMethod('reviewResults', sampleData.sampleId);
                                setTimeout(() => {
                                    window.location.href = '/results-review/' + sampleData.sampleId;
                                }, 100);
                            "
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Review Results</span>
                                <span class="text-xs text-gray-500">Review analysis results and findings</span>
                            </div>
                        </button>

                        <button x-show="sampleData.canApprove"
                            @click="
                                if (confirm('Are you sure you want to approve this sample? This action cannot be undone.')) {
                                    callLivewireMethod('approveSample', sampleData.sampleId);
                                }
                            "
                            class="flex items-center w-full px-3 py-2.5 text-sm text-green-700 hover:bg-green-50 hover:text-green-800 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Approve Sample</span>
                                <span class="text-xs text-gray-500">Final approval and sign-off</span>
                            </div>
                        </button>

                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100"></div>

                <!-- CoA Actions -->
                <div class="p-3" x-show="['approved', 'completed'].includes(sampleData.status) && sampleData.canCreateCoA">
                    <div class="px-2 py-1.5">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Certificate of Analysis</h4>
                    </div>
                    <div class="space-y-1">
                        <button @click="callLivewireMethod('openCoAForm', sampleData.sampleId)"
                            class="flex items-center w-full px-3 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-orange-100 group-hover:bg-orange-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Create CoA</span>
                                <span class="text-xs text-gray-500">Generate Certificate of Analysis</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-100" x-show="['approved', 'completed'].includes(sampleData.status) && sampleData.canCreateCoA"></div>

                <!-- Delete Actions -->
                <div class="p-3" >
                    <div class="px-2 py-1.5">
                        <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Danger Zone</h4>
                    </div>
                    <div class="space-y-1">
                        <button
                            @click="
                        if (confirm('Are you sure you want to delete this sample? This action cannot be undone.')) {
                            callLivewireMethod('deleteSample', sampleData.sampleId);
                        }
                    "
                            class="flex items-center w-full px-3 py-2.5 text-sm text-red-700 hover:bg-red-50 hover:text-red-800 rounded-lg transition-colors duration-150 group cursor-pointer">
                            <div
                                class="flex-shrink-0 w-9 h-9 bg-red-100 group-hover:bg-red-200 rounded-lg flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div class="flex-1 text-left">
                                <span class="font-medium block">Delete Sample</span>
                                <span class="text-xs text-gray-500">Permanently remove this sample</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Dropdown Logic -->
    <script>
        function sampleDropdown() {
            return {
                isOpen: false,
                sampleData: {},
                justOpened: false,

                config: {
                    dropdownWidth: 320,
                    dropdownHeight: 400,
                    margin: 8,
                    viewportMargin: 10,
                    transitionDuration: 150
                },

                openDropdown(sampleId, data) {
                    this.sampleData = this.createSampleData(sampleId, data);
                    if (data.buttonRect) this.calculatePosition(data.buttonRect);
                    this.showDropdown();
                },

                closeDropdown() {
                    this.isOpen = false;
                    setTimeout(() => this.resetData(), this.config.transitionDuration);
                },

                createSampleData(sampleId, data) {
                    const statusPermissions = {
                        canEdit: ['submitted', 'pending'].includes(data.status),
                        canStartAnalysis: ['submitted', 'pending'].includes(data.status),
                        canContinueAnalysis: ['in_progress'].includes(data.status),
                        canHandOver: ['in_progress'].includes(data.status),
                        canTakeOver: ['hand_over', 'hand over'].includes(data.status?.toLowerCase()) &&
                            data.handoverFromAnalystId != data.currentUserId,
                        canCompleteAnalysis: ['in_progress', 'analysis_started'].includes(data.status),
                        canReview: ['analysis_completed', 'pending_review'].includes(data.status),
                        canApprove: ['reviewed'].includes(data.status),
                        canCreateCoA: ['approved', 'completed'].includes(data.status),
                        canDelete: !['approved', 'completed'].includes(data.status)
                    };

                    return {
                        sampleId,
                        ...data,
                        ...statusPermissions,
                        position: {
                            left: 300,
                            top: 200
                        }
                    };
                },

                calculatePosition(buttonRect) {
                    const {
                        dropdownWidth,
                        dropdownHeight,
                        margin,
                        viewportMargin
                    } = this.config;
                    const viewport = {
                        width: window.innerWidth,
                        height: window.innerHeight
                    };

                    const space = {
                        left: buttonRect.left,
                        right: viewport.width - buttonRect.right,
                        above: buttonRect.top,
                        below: viewport.height - buttonRect.bottom
                    };

                    const left = space.left >= dropdownWidth ?
                        buttonRect.left - dropdownWidth - margin :
                        space.right >= dropdownWidth ?
                        buttonRect.right + margin :
                        (viewport.width - dropdownWidth) / 2;

                    const top = space.below >= dropdownHeight ?
                        buttonRect.bottom + margin :
                        space.above >= dropdownHeight ?
                        buttonRect.top - dropdownHeight - margin :
                        (viewport.height - dropdownHeight) / 2;

                    this.sampleData.position = {
                        left: Math.max(viewportMargin, Math.min(left, viewport.width - dropdownWidth - viewportMargin)),
                        top: Math.max(viewportMargin, Math.min(top, viewport.height - dropdownHeight - viewportMargin))
                    };
                },

                showDropdown() {
                    this.justOpened = true;
                    this.isOpen = true;
                    setTimeout(() => this.justOpened = false, 100);
                },

                resetData() {
                    this.sampleData = {};
                    this.justOpened = false;
                },

                handleClickAway() {
                    if (!this.justOpened) this.closeDropdown();
                },

                callLivewireMethod(method, sampleId) {
                    try {
                        // Find the main SampleSolderSubmission component
                        const mainComponent = document.querySelector('[wire\\:id][id*="sample"]');

                        console.log('Looking for Livewire component...', {
                            method,
                            sampleId,
                            mainComponent,
                            selector: '[wire:id][id*="sample"]'
                        });

                        if (mainComponent) {
                            const wireId = mainComponent.getAttribute('wire:id');
                            console.log('Found component with wire:id:', wireId);

                            const livewireComponent = window.Livewire.find(wireId);
                            console.log('Livewire component:', livewireComponent);

                            if (livewireComponent && typeof livewireComponent[method] === 'function') {
                                console.log(`Calling ${method}(${sampleId})`);
                                livewireComponent[method](sampleId);
                            } else {
                                console.error(`Method ${method} not found on component`, {
                                    method,
                                    componentFound: !!livewireComponent,
                                    methodExists: livewireComponent ? typeof livewireComponent[method] : 'N/A',
                                    availableMethods: livewireComponent ? Object.keys(livewireComponent).filter(k => typeof livewireComponent[k] === 'function') : 'Component not found'
                                });
                            }
                        } else {
                            console.error('Main Livewire component not found', {
                                selector: '[wire:id][id*="sample"]',
                                allWireComponents: document.querySelectorAll('[wire\\:id]')
                            });
                        }
                    } catch (error) {
                        console.error('Error calling Livewire method:', error);
                    }

                    this.closeDropdown();
                },

                initGlobalDropdown() {
                    // Override the placeholder with actual implementation
                    window.globalDropdown = {
                        open: (sampleId, data) => this.openDropdown(sampleId, data),
                        close: () => this.closeDropdown()
                    };
                    console.log('Global dropdown implementation loaded');
                }
            };
        }
    </script>
</div>
