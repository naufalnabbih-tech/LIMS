/**
 * Sample Actions Dropdown Component
 * Manages the dropdown menu for sample actions with positioning logic
 */
window.sampleDropdown = function() {
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

        /**
         * Open dropdown with sample data
         */
        openDropdown(sampleId, data) {
            this.sampleData = this.createSampleData(sampleId, data);
            if (data.buttonRect) this.calculatePosition(data.buttonRect);
            this.showDropdown();
        },

        /**
         * Close dropdown with transition
         */
        closeDropdown() {
            this.isOpen = false;
            setTimeout(() => this.resetData(), this.config.transitionDuration);
        },

        /**
         * Create sample data with permissions based on status
         */
        createSampleData(sampleId, data) {
            const statusPermissions = {
                canEdit: ['submitted', 'pending'].includes(data.status),
                canStartAnalysis: ['submitted', 'pending'].includes(data.status),
                canContinueAnalysis: ['in_progress'].includes(data.status),
                canHandOver: ['in_progress'].includes(data.status) &&
                             data.currentUserId === data.primaryAnalystId,
                canTakeOver: ['hand_over', 'hand over'].includes(data.status?.toLowerCase()) &&
                            data.handoverFromAnalystId != data.currentUserId,
                canCompleteAnalysis: ['in_progress', 'analysis_started'].includes(data.status),
                canReview: ['analysis_completed', 'pending_review'].includes(data.status),
                canApprove: ['reviewed'].includes(data.status),
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

        /**
         * Calculate optimal dropdown position based on button position
         */
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

        /**
         * Show dropdown with animation
         */
        showDropdown() {
            this.justOpened = true;
            this.isOpen = true;
            setTimeout(() => this.justOpened = false, 100);
        },

        /**
         * Reset dropdown data
         */
        resetData() {
            this.sampleData = {};
            this.justOpened = false;
        },

        /**
         * Handle click outside dropdown
         */
        handleClickAway() {
            if (!this.justOpened) this.closeDropdown();
        },

        /**
         * Call Livewire method on main component
         */
        callLivewireMethod(method, sampleId) {
            try {
                // Find the main SampleRawmatSubmission component by ID
                const mainComponent = document.querySelector('#sample-rawmat-submission-component[wire\\:id]');

                if (mainComponent) {
                    const wireId = mainComponent.getAttribute('wire:id');
                    const livewireComponent = window.Livewire.find(wireId);

                    if (livewireComponent && typeof livewireComponent[method] === 'function') {
                        livewireComponent.call(method, sampleId);
                    } else {
                        console.error(`Method ${method} not found on component`, {
                            method,
                            availableMethods: Object.keys(livewireComponent).filter(k => typeof livewireComponent[k] === 'function')
                        });
                    }
                } else {
                    console.error('Main Livewire component not found');
                }
            } catch (error) {
                console.error('Error calling Livewire method:', error);
            }

            this.closeDropdown();
        },

        /**
         * Initialize global dropdown instance
         */
        initGlobalDropdown() {
            window.globalDropdown = {
                open: (sampleId, data) => this.openDropdown(sampleId, data),
                close: () => this.closeDropdown()
            };
        }
    };
};
