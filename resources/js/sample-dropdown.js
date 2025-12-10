document.addEventListener('alpine:init', () => {
    Alpine.data('sampleDropdownLogic', () => ({
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

        init() {
            // Global helper untuk membuka/menutup dropdown dari tombol tabel
            window.globalDropdown = {
                open: (sampleId, data) => this.openDropdown(sampleId, data),
                close: () => this.closeDropdown()
            };
            console.log('Global dropdown initialized (Livewire v3)');
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
            // Rules permission berdasarkan status
            const statusPermissions = {
                canEdit: ['submitted', 'pending'].includes(data.status),
                canStartAnalysis: ['submitted', 'pending'].includes(data.status),
                canContinueAnalysis: ['in_progress'].includes(data.status),
                // Handover hanya untuk analis utama
                canHandOver: ['in_progress'].includes(data.status),
                // Takeover untuk analis penerima (bukan yang menyerahkan)
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
                position: { left: 300, top: 200 }
            };
        },

        calculatePosition(buttonRect) {
            const { dropdownWidth, dropdownHeight, margin, viewportMargin } = this.config;
            const viewport = { width: window.innerWidth, height: window.innerHeight };

            const space = {
                left: buttonRect.left,
                right: viewport.width - buttonRect.right,
                above: buttonRect.top,
                below: viewport.height - buttonRect.bottom
            };

            // Horizontal positioning
            const left = space.left >= dropdownWidth ?
                buttonRect.left - dropdownWidth - margin :
                space.right >= dropdownWidth ?
                buttonRect.right + margin :
                (viewport.width - dropdownWidth) / 2;

            // Vertical positioning
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

        /**
         * LIVEWIRE v3 CALLER
         * Dipanggil dari tombol aksi (Edit, Start, Continue, Takeover, dll)
         */
        callLivewireMethod(method, sampleId) {
            try {
                // Mencari div dengan ID sesuai modul
                const mainComponent = document.querySelector('div[id^="sample-"][id$="-submission-component"]');

                if (mainComponent) {
                    const wireId = mainComponent.getAttribute('wire:id');

                    // Livewire v3 → HARUS pakai getById()
                    const livewireComponent = Livewire.getById(wireId);

                    if (livewireComponent) {
                        livewireComponent.call(method, sampleId);
                    } else {
                        console.error(`Livewire component object not found for wire:id ${wireId}`);
                    }
                } else {
                    console.error('Parent Livewire component not found. Check ID in Blade.');
                    alert('Error: Could not find the main component on this page.');
                }
            } catch (error) {
                console.error('Error executing Livewire v3 action:', error);
            }

            this.closeDropdown();
        }
    }))
});
