import './bootstrap';
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';

window.Alpine = Alpine;
Alpine.plugin(focus);

// Initialize Alpine.js
document.addEventListener('DOMContentLoaded', () => {
    Alpine.start();

    // Add event listener for modal open
    window.addEventListener('open-modal', (event) => {
        const modalId = event.detail;
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
        }
    });

    // Add event listener for modal close
    window.addEventListener('close', (event) => {
        const modal = event.target.closest('[x-data]');
        if (modal) {
            modal.style.display = 'none';
        }
    });
});
