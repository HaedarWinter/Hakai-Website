<!-- Task Detail Modal -->
<div class="modal fade" id="modalDetailTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header border-bottom bg-white py-3 px-4">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-light p-2 rounded-circle mr-3">
                        <i class="fas fa-tasks text-primary"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-dark font-weight-bold mb-0">Detail Tugas</h5>
                        <p class="text-xs text-muted mb-0">Informasi lengkap mengenai pelaksanaan tugas</p>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-0" id="task-detail-content">
                <!-- Content will be loaded via AJAX -->
            </div>

            <!-- Comments Section -->
            <div class="modal-body border-top-0 pt-0" id="comments-section" style="display: none;">
                <h6 class="font-weight-bold text-primary mb-3 px-4">
                    <i class="fas fa-comments mr-2"></i>Komentar
                    <span class="badge badge-primary ml-1" id="comments-count">0</span>
                </h6>

                <!-- Comments List -->
                <div id="comments-list" class="px-4" style="max-height: 250px; overflow-y: auto;">
                    <div class="text-center text-muted py-3" id="no-comments">
                        <i class="fas fa-comment-slash fa-2x mb-2"></i>
                        <p class="mb-0">Belum ada komentar</p>
                    </div>
                </div>

                <!-- Add Comment Form -->
                <div class="px-4 pb-4">
                    <form id="add-comment-form" class="mt-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="comment-input" placeholder="Tulis komentar..."
                                maxlength="1000">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" id="submit-comment">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer border-0 bg-light px-4 py-3">
                <button type="button" class="btn btn-secondary px-4 font-weight-bold" data-dismiss="modal"
                    style="border-radius: 8px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #cbd5e0;
    }

    /* Detail Modal Styles - Premium System */
    .detail-section-label {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #64748b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .detail-section-label::after {
        content: "";
        height: 1px;
        flex: 1;
        background: radial-gradient(circle, #e2e8f0 0%, transparent 100%);
    }

    .info-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }

    .info-card:hover {
        border-color: #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    .description-box {
        background: #f8fafc;
        border-radius: 14px;
        padding: 0.75rem 1.25rem;
        font-size: 0.95rem;
        line-height: 1.5 !important;
        color: #334155;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #4e73df;
        white-space: pre-line;
        word-wrap: break-word;
        overflow-wrap: break-word;
        position: relative;
    }

    html.dark-mode .description-box {
        background: rgba(30, 41, 59, 0.5);
        border-color: #334155;
        color: #cbd5e0;
    }

    .status-pill-premium {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-approved {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .status-submitted {
        background: #e0f2fe;
        color: #075985;
        border: 1px solid #bae6fd;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    /* Attachment Cards v2 */
    .attachment-card-v2 {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
    }

    .attachment-card-v2:hover {
        background: #f8fafc;
        border-color: #cbd5e0;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .attachment-icon-v2 {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* Comment Unified Design */
    .comment-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 12px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9;
    }

    .comment-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .comment-item.comment-reply {
        background: #ffffff;
        border-left: 3px solid #4e73df;
        border-radius: 0 10px 10px 0;
    }

    .comment-item .comment-header {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .comment-item .comment-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .comment-item .comment-avatar img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .comment-item .comment-meta {
        flex: 1;
        min-width: 0;
    }

    .comment-item .comment-author {
        font-weight: 600;
        font-size: 13px;
        color: #3a3b45;
    }

    .comment-item .comment-time {
        font-size: 11px;
        color: #858796;
    }

    .comment-item .comment-actions {
        display: flex;
        align-items: center;
        opacity: 0.4;
        transition: opacity 0.2s;
    }

    .comment-item:hover .comment-actions {
        opacity: 1;
    }

    .comment-item .comment-content {
        font-size: 14px;
        color: #5a5c69;
        line-height: 1.5;
        word-wrap: break-word;
    }

    /* Preview Styles */
    .preview-card {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .preview-header {
        padding: 0.75rem 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .preview-header span {
        font-size: 0.85rem;
        font-weight: 700;
        color: #334155;
    }

    .preview-header .mode-label {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 800;
    }

    /* Utility Classes */
    .bg-primary-light {
        background-color: rgba(78, 115, 223, 0.1) !important;
    }

    .bg-success-light {
        background-color: rgba(34, 197, 94, 0.1) !important;
    }

    .bg-danger-light {
        background-color: rgba(239, 68, 68, 0.1) !important;
    }

    .bg-warning-light {
        background-color: rgba(245, 158, 11, 0.1) !important;
    }

    .bg-info-light {
        background-color: rgba(8, 145, 178, 0.1) !important;
    }

    .text-primary {
        color: #4e73df !important;
    }

    .text-success {
        color: #16a34a !important;
    }

    .text-danger {
        color: #dc2626 !important;
    }

    .text-warning {
        color: #d97706 !important;
    }

    .text-info {
        color: #0891b2 !important;
    }

    .border-dashed {
        border: 1px dashed #e2e8f0;
    }

    .opacity-5 {
        opacity: 0.5;
    }

    /* Toggle Styles */
    .btn-preview-toggle {
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        background: white;
        color: #475569;
    }

    .btn-preview-toggle:hover {
        background: #f8fafc;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    .btn-preview-toggle.active {
        background: #f0fdf4 !important;
        color: #166534 !important;
        border-color: #bbf7d0 !important;
    }

    .preview-collapse {
        overflow: hidden;
        transition: all 0.3s ease-in-out;
        max-height: 0;
        opacity: 0;
    }

    .preview-collapse.show {
        max-height: 2000px;
        opacity: 1;
        margin-bottom: 1.5rem;
    }

    /* Dark Mode Overrides */
    html.dark-mode .modal-header {
        background: #1e293b !important;
        border-color: #334155 !important;
    }

    html.dark-mode .modal-header .modal-title {
        color: #f1f5f9 !important;
    }

    html.dark-mode .detail-section-label {
        color: #94a3b8;
    }

    html.dark-mode .info-card,
    html.dark-mode .preview-card,
    html.dark-mode .attachment-card-v2 {
        background: #1e293b;
        border-color: #334155;
    }

    html.dark-mode .attachment-card-v2:hover {
        background: #334155;
        border-color: #475569;
    }

    html.dark-mode .attachment-icon-v2 {
        background: #0f172a !important;
    }

    html.dark-mode .info-card .text-dark,
    html.dark-mode .attachment-card-v2 .text-dark {
        color: #f1f5f9 !important;
    }

    html.dark-mode .preview-header {
        background: #1e293b;
        border-bottom: 1px solid #334155;
        color: #e2e8f0;
    }

    html.dark-mode .preview-body {
        background: #0f172a !important;
    }

    /* Dark Mode Status */
    html.dark-mode .status-approved {
        background: rgba(22, 101, 52, 0.2);
        color: #4ade80;
        border-color: rgba(74, 222, 128, 0.05);
    }

    html.dark-mode .status-submitted {
        background: rgba(7, 89, 133, 0.2);
        color: #38bdf8;
        border-color: rgba(56, 189, 248, 0.05);
    }

    html.dark-mode .status-rejected {
        background: rgba(153, 27, 27, 0.2);
        color: #f87171;
        border-color: rgba(248, 113, 113, 0.05);
    }

    html.dark-mode .status-pending {
        background: rgba(146, 64, 14, 0.2);
        color: #fbbf24;
        border-color: rgba(251, 191, 36, 0.05);
    }

    /* Dark Mode Comments */
    html.dark-mode .comment-item {
        background: #1e293b !important;
        border: 1px solid #334155;
    }

    html.dark-mode .comment-item.comment-reply {
        background: #0f172a !important;
        border-left-color: #4e73df !important;
    }

    html.dark-mode .comment-item .comment-author {
        color: #e2e8f0 !important;
    }

    html.dark-mode .comment-item .comment-time {
        color: #94a3b8 !important;
    }

    html.dark-mode .comment-item .comment-content {
        color: #cbd5e0 !important;
    }
</style>