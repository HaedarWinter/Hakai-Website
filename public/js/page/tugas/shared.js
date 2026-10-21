window.HakaiTugas = (function ($) {
    'use strict';

    const MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    const DEFAULT_AVATAR = '/sbadmin2/img/undraw_profile.svg';

    function parseDate(dateString) {
        if (!dateString) return null;
        const date = dateString.includes('T')
            ? new Date(dateString)
            : new Date(dateString.replace(' ', 'T'));
        return isNaN(date.getTime()) ? null : date;
    }

    function formatDateTime(dateString) {
        const date = parseDate(dateString);
        if (!date) return dateString || '-';

        return `${date.getDate()} ${MONTHS[date.getMonth()]} ${date.getFullYear()}, ` +
            `${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
    }

    function formatDateTimeLocal(dateString) {
        const date = parseDate(dateString);
        if (!date) return '';

        const month = String(date.getMonth() + 1).padStart(2, '0');
        return `${date.getFullYear()}-${month}-${String(date.getDate()).padStart(2, '0')}T` +
            `${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
    }

    function bindFileInputLabel() {
        $('.custom-file-input').on('change', function () {
            const fileName = $(this).val().split('\\').pop();
            $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
        });
    }

    function bindGlobalSearch(table) {
        $('#globalSearchInput').on('keyup', function () {
            table.search(this.value).draw();
        });
    }

    function statusPill(status) {
        if (status === 'approved') {
            return '<span class="status-pill-premium status-pill-success"><i class="fas fa-check-circle"></i> Disetujui</span>';
        }
        if (status === 'submitted') {
            return '<span class="status-pill-premium status-pill-info"><i class="fas fa-clock"></i> Menunggu</span>';
        }
        if (status === 'rejected') {
            return '<span class="status-pill-premium status-pill-danger"><i class="fas fa-times-circle"></i> Ditolak</span>';
        }
        return '<span class="status-pill-premium status-pill-warning"><i class="fas fa-hourglass-half"></i> Pending</span>';
    }

    function detailStatusBadge(status, submittedLabel) {
        if (status === 'approved') {
            return '<span class="status-pill-premium status-approved"><i class="fas fa-check-circle mr-1"></i> Disetujui</span>';
        }
        if (status === 'submitted') {
            return `<span class="status-pill-premium status-submitted"><i class="fas fa-hourglass-half mr-1"></i> ${submittedLabel || 'Menunggu'}</span>`;
        }
        if (status === 'rejected') {
            return '<span class="status-pill-premium status-rejected"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>';
        }
        return '<span class="status-pill-premium status-pending"><i class="fas fa-clock mr-1"></i> Pending</span>';
    }

    function filePreview(fileName, label) {
        if (!fileName) return '';

        const fileExt = fileName.split('.').pop().toLowerCase();
        const filePath = '/storage/' + fileName;
        const previewId = 'preview-' + Math.random().toString(36).substr(2, 9);

        let content = '';
        if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
            content = `<img src="${filePath}" class="img-fluid" style="max-height: 500px; width: 100%; object-fit: contain; background: #0f172a;" alt="Preview">`;
        } else if (fileExt === 'pdf') {
            content = `<div class="embed-responsive embed-responsive-16by9" style="height: 500px;"><iframe class="embed-responsive-item" src="${filePath}" allowfullscreen></iframe></div>`;
        }

        if (!content) return '';

        return `
            <div class="row align-items-center mb-3 animate-in">
                <div class="col-12">
                    <div class="p-3 bg-light rounded border-dashed d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-light p-2 rounded mr-3">
                                <i class="fas fa-eye text-primary"></i>
                            </div>
                            <div>
                                <div class="small font-weight-bold text-dark">${label}</div>
                                <div class="text-xs text-muted">Pratinjau Dokumen</div>
                            </div>
                        </div>
                        <button class="btn btn-preview-toggle" type="button" data-toggle-id="${previewId}">
                            <i class="fas fa-eye"></i> <span>Lihat Pratinjau</span>
                        </button>
                    </div>
                    <div id="${previewId}" class="preview-collapse">
                        <div class="preview-card mt-3 shadow-sm">
                            <div class="preview-header">
                                <span><i class="fas fa-search mr-2"></i>Pratinjau File</span>
                                <div class="mode-label">Mode Tampilan</div>
                            </div>
                            <div class="preview-body p-0 bg-dark">
                                ${content}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function fileCard(fileName, label, colorClass, iconClass) {
        if (!fileName) return '';

        return `
            <div class="attachment-card-v2 animate-in">
                <div class="attachment-icon-v2 bg-${colorClass}-light text-${colorClass}">
                    <i class="${iconClass}"></i>
                </div>
                <div class="flex-grow-1 overflow-hidden" style="min-width: 0;">
                    <div class="small font-weight-bold text-dark text-truncate">${label}</div>
                    <div class="text-xs text-muted text-truncate">${fileName.split('/').pop()}</div>
                </div>
                <div class="ml-2">
                    <a href="/storage/${fileName}" target="_blank" class="btn btn-sm btn-light shadow-sm" style="border-radius: 8px;" title="Download">
                        <i class="fas fa-download text-primary"></i>
                    </a>
                </div>
            </div>
        `;
    }

    function renderTaskDetail(task, options) {
        const opts = options || {};
        const person = opts.person || null;
        const personName = person ? person.nama : 'N/A';
        const personEmail = person ? person.email : '-';
        const personPhoto = person && person.profile_photo_path
            ? '/storage/' + person.profile_photo_path
            : DEFAULT_AVATAR;

        const priority = task.priority;
        const priorityColor = priority === 'high' ? 'danger' : priority === 'medium' ? 'warning' : 'secondary';
        const priorityLabel = priority === 'high' ? 'Tinggi' : priority === 'medium' ? 'Sedang' : 'Rendah';

        const adminFileCard = fileCard(task.tugas_file, 'Instruksi Admin', 'primary', 'fas fa-file-alt');
        const employeeFileCard = fileCard(task.karyawan_file, opts.employeeFileLabel || 'Laporan Karyawan', 'success', 'fas fa-file-upload');
        const previews = filePreview(task.tugas_file, 'Pratinjau Instruksi Admin') +
            filePreview(task.karyawan_file, 'Pratinjau Hasil Karyawan');

        return `
            <div class="px-4 py-4">
                <div class="mb-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge badge-primary-light text-primary px-2 py-1 mr-2" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">ID: #${task.id}</span>
                        ${priority ? `
                            <span class="badge badge-${priorityColor}-light text-${priorityColor} px-2 py-1" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">
                                <i class="fas fa-flag mr-1"></i> Prioritas ${priorityLabel}
                            </span>
                        ` : ''}
                    </div>
                    <h4 class="font-weight-bold text-dark mb-1" title="${task.tugas}">${task.tugas.length > 100 ? task.tugas.substring(0, 100) + '...' : task.tugas}</h4>
                    <p class="text-xs text-muted mb-0"><i class="far fa-calendar-alt mr-1"></i> Dibuat pada ${formatDateTime(task.created_at)}</p>
                </div>

                <div class="row mb-5">
                    <div class="col-md-7 mb-3 mb-md-0">
                        <span class="detail-section-label">${opts.personLabel || 'Informasi Karyawan'}</span>
                        <div class="info-card p-3 d-flex align-items-center h-100">
                            <div class="mr-3 position-relative">
                                <img class="img-profile rounded-circle shadow-sm border"
                                     style="width: 54px; height: 54px; object-fit: cover;"
                                     src="${personPhoto}"
                                     alt="Profile">
                                <div class="position-absolute" style="bottom: -2px; right: -2px;">
                                    <div class="${opts.personDotClass || 'bg-success'} rounded-circle border border-white" style="width: 12px; height: 12px;"></div>
                                </div>
                            </div>
                            <div class="overflow-hidden">
                                <div class="font-weight-bold text-dark text-truncate" style="font-size: 1.05rem;">${personName}</div>
                                <div class="small text-muted text-truncate mb-1">${personEmail}</div>
                                <span class="badge badge-primary-light text-primary border text-xs px-2 py-1" style="border-radius: 4px;">${opts.personBadge || 'Penerima Tugas'}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <span class="detail-section-label">Periode & Status</span>
                        <div class="info-card p-3 h-100">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="text-xs font-weight-bold text-muted"><i class="far fa-clock mr-1"></i> Mulai</div>
                                <div class="small font-weight-bold text-dark">${formatDateTime(task.tanggal_mulai)}</div>
                            </div>
                            <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                                <div class="text-xs font-weight-bold text-muted"><i class="far fa-calendar-check mr-1"></i> Selesai</div>
                                <div class="small font-weight-bold text-dark">${formatDateTime(task.tanggal_selesai)}</div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-2">
                                <div class="text-xs font-weight-bold text-muted">Status</div>
                                ${detailStatusBadge(task.status, opts.submittedLabel)}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12">
                        <span class="detail-section-label">Deskripsi Tugas</span>
                        <div class="description-box shadow-sm">${(task.tugas ? task.tugas.trim() : '') || '<span class="text-muted italic">Tidak ada deskripsi tugas.</span>'}</div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-12">
                        <span class="detail-section-label">File Lampiran</span>
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                ${adminFileCard || `
                                    <div class="p-3 bg-light rounded text-center text-muted small border-dashed h-100 d-flex align-items-center justify-content-center">
                                        <div><i class="fas fa-file-circle-exclamation mr-1 opacity-5"></i> Tidak ada file instruksi</div>
                                    </div>
                                `}
                            </div>
                            <div class="col-md-6">
                                ${employeeFileCard || `
                                    <div class="p-3 bg-light rounded text-center text-muted small border-dashed h-100 d-flex align-items-center justify-content-center">
                                        <div><i class="fas fa-file-circle-exclamation mr-1 opacity-5"></i> Tidak ada file laporan</div>
                                    </div>
                                `}
                            </div>
                        </div>
                    </div>
                </div>

                ${task.catatan ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="p-3 border-left border-danger bg-danger-light rounded shadow-sm" style="border-left-width: 4px !important;">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Catatan Penolakan</div>
                            <div class="text-dark small"><i class="fas fa-exclamation-circle mr-2"></i>${task.catatan}</div>
                        </div>
                    </div>
                </div>` : ''}

                ${previews ? `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="preview-section">
                            <div class="preview-header-wrapper d-flex align-items-center mb-3">
                                <span class="detail-section-label mb-0 mr-auto">Pratinjau File</span>
                            </div>
                            ${previews}
                        </div>
                    </div>
                </div>` : ''}
            </div>
        `;
    }

    function initPreviewToggle() {
        $(document).on('click', '.btn-preview-toggle', function () {
            const $target = $('#' + $(this).data('toggle-id'));
            $('.preview-collapse').not($target).removeClass('show');
            $('.btn-preview-toggle').not(this).removeClass('active');
            $target.toggleClass('show');
            $(this).toggleClass('active');
        });
    }

    function initComments() {
        let currentTaskId = null;
        let replyingToId = null;

        function countAll(comments) {
            return comments.reduce(function (count, c) {
                return count + 1 + (c.replies && c.replies.length ? countAll(c.replies) : 0);
            }, 0);
        }

        function renderItems(comments, depth) {
            const marginLeft = depth > 0 ? depth * 20 : 0;
            const bgClass = depth > 0 ? 'comment-reply' : '';

            return comments.map(function (c) {
                const user = c.user || { nama: 'Pengguna dihapus', photo: null };
                const avatar = user.photo
                    ? `<img src="${user.photo}" alt="${user.nama}">`
                    : user.nama.charAt(0).toUpperCase();

                let html = `
                    <div class="comment-item ${bgClass}" data-id="${c.id}" style="margin-left: ${marginLeft}px;">
                        <div class="comment-header">
                            <div class="comment-avatar">${avatar}</div>
                            <div class="comment-meta">
                                <div class="comment-author">${user.nama}</div>
                                <div class="comment-time">${c.created_at_human}</div>
                            </div>
                            <div class="comment-actions">
                                <button class="btn btn-sm btn-link text-primary btn-reply-comment p-0 mr-2"
                                        data-id="${c.id}" data-name="${user.nama}" title="Balas">
                                    <i class="fas fa-reply"></i>
                                </button>
                                ${c.is_own ? `
                                    <button class="btn btn-sm btn-link text-danger btn-delete-comment p-0" data-id="${c.id}" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                        <div class="comment-content">${c.content}</div>
                    </div>
                `;

                if (c.replies && c.replies.length > 0 && depth < 2) {
                    html += renderItems(c.replies, depth + 1);
                }

                return html;
            }).join('');
        }

        function load(taskId) {
            currentTaskId = taskId;
            replyingToId = null;
            $('#comments-section').show();
            $('#comments-list').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>');

            $.ajax({
                url: `/api/tugas/${taskId}/comments`,
                type: 'GET',
                cache: false,
                success: function (res) {
                    if (!res.success) return;

                    $('#comments-count').text(countAll(res.data));

                    if (res.data.length === 0) {
                        $('#comments-list').html(`
                            <div class="text-center text-muted py-3" id="no-comments">
                                <i class="fas fa-comment-slash fa-2x mb-2"></i>
                                <p class="mb-0">Belum ada komentar</p>
                            </div>
                        `);
                        return;
                    }

                    $('#comments-list').html(renderItems(res.data, 0));
                    $('#comments-list').scrollTop($('#comments-list')[0].scrollHeight);
                },
                error: function () {
                    $('#comments-list').html('<div class="text-center text-danger py-3"><i class="fas fa-exclamation-circle"></i> Gagal memuat komentar</div>');
                }
            });
        }

        function sessionExpired(xhr) {
            if (xhr.status !== 419 && xhr.status !== 401) return false;

            Swal.fire({
                icon: 'warning',
                title: 'Sesi Berakhir',
                text: 'Sesi anda telah berakhir. Halaman akan dimuat ulang.',
                timer: 2000,
                showConfirmButton: false
            }).then(function () {
                location.reload();
            });
            return true;
        }

        function reset() {
            currentTaskId = null;
            replyingToId = null;
            $('#comments-section').hide();
            $('#comments-list').html('');
            $('#comment-input').val('').attr('placeholder', 'Tulis komentar...');
            $('#cancel-reply').parent().remove();
        }

        $(document).on('click', '.btn-reply-comment', function () {
            replyingToId = $(this).data('id');
            $('#comment-input').attr('placeholder', `Balas ke ${$(this).data('name')}...`).focus();

            if ($('#cancel-reply').length === 0) {
                $('#add-comment-form .input-group').prepend(`
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-outline-secondary" id="cancel-reply" title="Batal balas">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `);
            }
        });

        $(document).on('click', '#cancel-reply', function () {
            replyingToId = null;
            $('#comment-input').attr('placeholder', 'Tulis komentar...').val('');
            $(this).parent().remove();
        });

        $('#add-comment-form').on('submit', function (e) {
            e.preventDefault();
            const content = $('#comment-input').val().trim();
            if (!content || !currentTaskId) return;

            const postData = { content: content };
            if (replyingToId) postData.parent_id = replyingToId;

            const $btn = $('#submit-comment');
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: `/api/tugas/${currentTaskId}/comments`,
                type: 'POST',
                data: postData,
                success: function (res) {
                    if (!res.success) return;
                    $('#comment-input').val('').attr('placeholder', 'Tulis komentar...');
                    $('#cancel-reply').parent().remove();
                    replyingToId = null;
                    load(currentTaskId);
                },
                error: function (xhr) {
                    if (sessionExpired(xhr)) return;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: xhr.responseJSON?.message || 'Gagal menambah komentar'
                    });
                },
                complete: function () {
                    $btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>');
                }
            });
        });

        $(document).on('click', '.btn-delete-comment', function () {
            const commentId = $(this).data('id');

            Swal.fire({
                title: 'Hapus Komentar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (!result.isConfirmed) return;

                $.ajax({
                    url: `/api/tugas/comments/${commentId}`,
                    type: 'DELETE',
                    success: function () {
                        load(currentTaskId);
                    },
                    error: function (xhr) {
                        if (xhr.status === 404) {
                            load(currentTaskId);
                            return;
                        }
                        if (sessionExpired(xhr)) return;
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Gagal menghapus komentar'
                        });
                    }
                });
            });
        });

        $('#modalDetailTask').on('hidden.bs.modal', reset);

        return { load: load, reset: reset };
    }

    return {
        formatDateTime: formatDateTime,
        formatDateTimeLocal: formatDateTimeLocal,
        bindFileInputLabel: bindFileInputLabel,
        bindGlobalSearch: bindGlobalSearch,
        statusPill: statusPill,
        detailStatusBadge: detailStatusBadge,
        filePreview: filePreview,
        fileCard: fileCard,
        renderTaskDetail: renderTaskDetail,
        initPreviewToggle: initPreviewToggle,
        initComments: initComments
    };
})(jQuery);
