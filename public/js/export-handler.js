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

        // Fetch first so a failed export shows an error instead of a fake success.
        fetch(url, {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(async (response) => {
                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const blob = await response.blob();
                const disposition = response.headers.get('Content-Disposition') || '';
                const match = /filename="?([^";]+)"?/.exec(disposition);
                const filename = match ? match[1] : `${type}-export`;

                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                link.remove();
                URL.revokeObjectURL(link.href);

                Toast.fire({
                    icon: 'success',
                    title: `Export ${type} berhasil!`,
                    text: 'File akan segera diunduh'
                });
            })
            .catch(() => {
                Toast.fire({
                    icon: 'error',
                    title: `Export ${type} gagal`,
                    text: 'Silakan coba lagi'
                });
            });
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
