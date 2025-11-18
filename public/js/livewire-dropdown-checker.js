// Ensure globalDropdown is available even if component hasn't initialized yet
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit for Livewire components to initialize
    setTimeout(() => {
        if (!window.globalDropdown) {
            console.warn('Global dropdown not initialized. Waiting for component...');
        }
    }, 100);
});