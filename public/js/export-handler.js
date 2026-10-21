/**
 * Export Handler with Toast Notifications
 * Handles PDF and Excel exports with user feedback
 */

(function () {
    'use strict';

    // Toast configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    /**
     * Handle export action
     * @param {string} url - Export URL
     * @param {string} type - Export type (Excel/PDF)
     */
    function handleExport(url, type) {
        // Show loading toast
        Toast.fire({
            icon: 'info',
            title: `Memproses export ${type}...`,
            timer: 1500
        });

        // Create hidden iframe for download
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = url;
        document.body.appendChild(iframe);

        // Show success toast after a short delay
        setTimeout(() => {
            Toast.fire({
                icon: 'success',
                title: `Export ${type} berhasil!`,
                text: 'File akan segera diunduh'
            });

            // Remove iframe after download starts
            setTimeout(() => {
                document.body.removeChild(iframe);
            }, 2000);
        }, 1000);
    }

    /**
     * Initialize export buttons
     */
    function initExportButtons() {
        // Handle all export buttons
        document.querySelectorAll('[data-export-type]').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('href') || this.getAttribute('data-url');
                const type = this.getAttribute('data-export-type');
                handleExport(url, type);
            });
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initExportButtons);
    } else {
        initExportButtons();
    }

    // Export to global scope for manual usage
    window.handleExport = handleExport;
})();
