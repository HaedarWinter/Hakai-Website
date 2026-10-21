
$(document).ready(function () {
    const { formatDateTime, formatDateTimeLocal, renderTaskDetail, initComments, initPreviewToggle } = window.HakaiTugas;

    window.HakaiTugas.bindFileInputLabel();
    const comments = initComments();
    initPreviewToggle();

    // Initialize DataTable
    $('head').append('<style>.user-profile-link:hover { background-color: #f8f9fc !important; text-decoration: none; } .user-profile-link:hover .text-primary { text-decoration: underline; }</style>');
    const State = {
        hasError: false,
        intervalId: null,
    };

    const sortColumns = {
        3: 'tugas',
        4: 'tanggal_selesai',
        6: 'status'
    };

    const taskTable = $('#taskTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        responsive: false, // Turn off responsive to force scrolling
        autoWidth: false, // Respect CSS widths
        pageLength: 10,
        order: [],
        language: {
            url: '/assets/datatables/i18n/id.json'
        },
        ajax: {
            url: '/api/tugas',
            data: function (d) {
                const order = d.order && d.order[0];
                return {
                    page: Math.floor(d.start / d.length) + 1,
                    per_page: d.length,
                    q: d.search.value,
                    status: $('#filter-status').val(),
                    user_id: $('#filter-user').val(),
                    priority: $('#filter-priority').val(),
                    sort: order ? sortColumns[order.column] : undefined,
                    dir: order && sortColumns[order.column] ? order.dir : undefined,
                    _t: Date.now()
                };
            },
            dataSrc: function (json) {
                if (!json.success) return [];

                State.hasError = false;
                json.recordsTotal = json.meta.stats.total;
                json.recordsFiltered = json.meta.total;
                updateStats(json.meta.stats);

                const start = (json.meta.current_page - 1) * json.meta.per_page;

                return json.data.map(function (task, index) {
                    let statusBadge = '';
                    let statusClass = '';
                    let iconClass = '';

                    if (task.status === 'approved') {
                        statusBadge = 'Disetujui'; statusClass = 'success'; iconClass = 'fa-check-circle';
                    } else if (task.status === 'submitted') {
                        statusBadge = 'Menunggu'; statusClass = 'info'; iconClass = 'fa-clock';
                    } else if (task.status === 'rejected') {
                        statusBadge = 'Ditolak'; statusClass = 'danger'; iconClass = 'fa-times-circle';
                    } else {
                        statusBadge = 'Pending'; statusClass = 'warning'; iconClass = 'fa-hourglass-half';
                    }

                    const formattedStartDate = formatDateTime(task.tanggal_mulai);
                    const formattedEndDate = formatDateTime(task.tanggal_selesai);

                    const actionButtons = `
                        <div class="dropdown no-arrow">
                            <button class="btn btn-sm btn-light btn-circle shadow-sm dropdown-toggle" type="button"
                                    id="taskAction-${task.id}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v text-gray-600"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="taskAction-${task.id}">
                                <div class="dropdown-header">Kelola Tugas:</div>
                                <a class="dropdown-item btn-detail" href="#" data-id="${task.id}">
                                    <i class="fas fa-eye fa-sm fa-fw mr-2 text-info"></i> Lihat Detail
                                </a>
                                <a class="dropdown-item btn-edit" href="#" data-id="${task.id}">
                                    <i class="fas fa-edit fa-sm fa-fw mr-2 text-warning"></i> Edit Tugas
                                </a>
                                ${task.status === 'submitted' ? `
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item btn-approve text-success" href="#" data-id="${task.id}" data-nama="${task.user.nama}">
                                        <i class="fas fa-check fa-sm fa-fw mr-2"></i> Setujui
                                    </a>
                                    <a class="dropdown-item btn-reject text-danger" href="#" data-id="${task.id}" data-nama="${task.user.nama}">
                                        <i class="fas fa-times fa-sm fa-fw mr-2"></i> Tolak
                                    </a>
                                ` : ''}
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item btn-delete text-danger" href="#" data-id="${task.id}">
                                    <i class="fas fa-trash fa-sm fa-fw mr-2"></i> Hapus
                                </a>
                            </div>
                        </div>
                    `;

                    const checkboxHtml = `
                        <div class="custom-control custom-checkbox text-center">
                            <input type="checkbox" class="custom-control-input task-checkbox"
                                id="customCheck${task.id}"
                                data-id="${task.id}"
                                data-status="${task.status}">
                            <label class="custom-control-label" for="customCheck${task.id}"></label>
                        </div>
                    `;

                    const photoUrl = task.user && task.user.profile_photo_path ? `/storage/${task.user.profile_photo_path}` : null;
                    const name = task.user ? task.user.nama : 'N/A';
                    const email = task.user ? task.user.email : '-';
                    const avatarHtml = photoUrl
                        ? `<img class="img-profile rounded-circle shadow-sm" src="${photoUrl}" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid #fff;">`
                        : `<div class="icon-circle shadow-sm bg-primary text-white" style="width: 38px; height: 38px;"><i class="fas fa-user"></i></div>`;

                    return [
                        start + index + 1,
                        checkboxHtml,
                        `<div class="d-flex align-items-center p-1 user-profile-link"
                             style="cursor: pointer; transition: all 0.2s; border-radius: 8px;"
                             onclick="window.location.href='/profile/${task.user ? task.user.id : ''}'"
                             data-search="${name} ${email}">
                            <div class="mr-3">${avatarHtml}</div>
                            <div class="min-width-0">
                                <div class="font-weight-bold text-primary text-truncate mb-0" style="max-width: 150px;">${name}</div>
                                <div class="small text-muted text-truncate" style="max-width: 150px;">${email}</div>
                            </div>
                        </div>`,
                        `<a href="javascript:void(0)" class="task-link btn-detail" data-id="${task.id}">
                            <div class="task-title font-weight-bold" title="${task.tugas}" style="color: #4e73df;">${task.tugas}</div>
                        </a>`,
                        `<div class="text-center small">
                            <div class="text-nowrap mb-1"><i class="fas fa-calendar-alt text-gray-400 mr-2"></i> ${formattedStartDate}</div>
                            <div class="text-nowrap"><i class="fas fa-calendar-check text-gray-400 mr-2"></i> ${formattedEndDate}</div>
                        </div>`,
                        `<div class="d-flex justify-content-center" style="gap: 5px;">
                            ${task.tugas_file ? `<a href="/storage/${task.tugas_file}" target="_blank" class="btn btn-xs btn-outline-primary shadow-sm" title="File Admin"><i class="fas fa-file-export"></i></a>` : ''}
                            ${task.karyawan_file ? `<a href="/storage/${task.karyawan_file}" target="_blank" class="btn btn-xs btn-outline-success shadow-sm" title="File Karyawan"><i class="fas fa-file-import"></i></a>` : ''}
                            ${(!task.tugas_file && !task.karyawan_file) ? '<span class="text-muted small">-</span>' : ''}
                        </div>`,
                        `<span class="status-pill-premium status-pill-${statusClass}"><i class="fas ${iconClass}"></i> ${statusBadge}</span>`,
                        actionButtons
                    ];
                });
            },
            error: function (xhr) {
                State.hasError = true;
                const message = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Gagal memuat data tugas.';
                showError(message);
            }
        },
        columnDefs: [
            { targets: 0, orderable: false, className: 'col-no d-none d-md-table-cell text-center', width: '50px' },
            { targets: 1, visible: false, orderable: false, className: 'col-checkbox', width: '40px' },
            { targets: 2, className: 'col-karyawan', width: '180px', orderable: false },
            { targets: 3, className: 'col-tugas' }, // Auto width
            { targets: 4, className: 'col-periode', width: '170px' },
            { targets: 5, className: 'col-file text-center', width: '100px', orderable: false },
            { targets: 6, className: 'col-status text-center', width: '130px' },
            { targets: 7, className: 'col-aksi text-center', width: '200px', orderable: false }
        ],
        dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row"<"col-sm-5"i><"col-sm-7"p>>',
        initComplete: function () {
            $('.dataTables_filter input').addClass('form-control-sm');
            $('.dataTables_filter input').attr('placeholder', 'Cari tugas...');
            $('.dataTables_filter label').contents().filter(function () {
                return this.nodeType === 3;
            }).replaceWith('<i class="fas fa-search fa-sm"></i>');
        }
    });

    // Connect Global Search Input to DataTable
    window.HakaiTugas.bindGlobalSearch(taskTable);

    // Variable to store original task data for comparison
    let originalTaskData = {};

    function updateStats(stats) {
        $('#total-tugas').text(stats.total);
        $('#total-selesai').text(stats.selesai);
        $('#total-proses').text(stats.proses);
        $('#total-pending').text(stats.pending);
    }

    function showError(message) {
        State.hasError = true;
        $('#task-body').html(`
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                        <p class="text-danger mb-3">${message}</p>
                        <button class="btn btn-sm btn-primary" onclick="loadTasks(true)">
                            <i class="fas fa-sync-alt mr-1"></i> Coba Lagi
                        </button>
                    </td>
                </tr>
            `);
    }

    window.loadTasks = function () {
        taskTable.ajax.reload(null, false);
    };

    // Auto Refresh
    function startAutoRefresh() {
        if (State.intervalId) clearInterval(State.intervalId);
        State.intervalId = setInterval(() => {
            if (document.hidden) return;
            if (!State.hasError) loadTasks();
        }, 30000);
    }

    function stopAutoRefresh() {
        if (State.intervalId) {
            clearInterval(State.intervalId);
            State.intervalId = null;
        }
    }
    // Filter Logic
    $('#filter-status, #filter-user, #filter-priority').on('change', function () {
        taskTable.ajax.reload();
    });

    $('#reset-filter').on('click', function () {
        $('#filter-status, #filter-user, #filter-priority').val('');
        $('.dataTables_filter input').val('');
        taskTable.search('').ajax.reload();
    });

    // CREATE TASK
    $('#form-add-task').on('submit', function (e) {
        e.preventDefault();
        stopAutoRefresh();
        let formData = new FormData(this);

        const $submitBtn = $(this).find('button[type="submit"]');
        const originalText = $submitBtn.html();
        $submitBtn.html('<span class="spinner-border spinner-border-sm mr-1"></span> Menyimpan...');
        $submitBtn.prop('disabled', true);

        $.ajax({
            url: "/api/tugas",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.success) {
                    $('#modalAddTask').modal('hide');
                    $('#form-add-task')[0].reset();
                    $(".custom-file-label").html("Pilih file");
                    loadTasks(true);
                    startAutoRefresh();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2000, showConfirmButton: false });
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors || {};
                let errorText = '<ul class="list-unstyled text-left">';
                for (const [key, value] of Object.entries(errors)) {
                    errorText += `<li><i class="fas fa-times-circle text-danger mr-1"></i> ${value[0]}</li>`;
                }
                errorText += '</ul>';
                Swal.fire({ icon: 'error', title: 'Validasi Gagal', html: errorText || 'Terjadi kesalahan' });
            },
            complete: function () {
                $submitBtn.html(originalText);
                $submitBtn.prop('disabled', false);
            }
        });
    });

    // DELETE TASK
    $(document).on('click', '.btn-delete', function () {
        let taskId = $(this).data('id');
        Swal.fire({
            title: 'Hapus Tugas?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger px-4 mr-2',
                cancelButton: 'btn btn-outline-dark px-4'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                stopAutoRefresh();
                $.ajax({
                    url: "/api/tugas/" + taskId,
                    type: "DELETE",
                    success: function (res) {
                        loadTasks(true);
                        startAutoRefresh();
                        Swal.fire({ icon: 'success', title: 'Terhapus!', text: res.message, timer: 2000, showConfirmButton: false });
                    },
                    error: function (xhr) {
                        startAutoRefresh();
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    }
                });
            }
        });
    });

    // EDIT TASK - GET DATA
    $(document).on('click', '.btn-edit', function () {
        let taskId = $(this).data('id');
        Swal.showLoading();
        $.ajax({
            url: "/api/tugas/" + taskId,
            type: "GET",
            success: function (res) {
                if (res.success) {
                    let t = res.data;

                    // Store original data for comparison
                    originalTaskData = {
                        id: t.id,
                        tugas: t.tugas,
                        tanggal_mulai: formatDateTimeLocal(t.tanggal_mulai),
                        tanggal_selesai: formatDateTimeLocal(t.tanggal_selesai),
                        priority: t.priority || 'medium'
                    };

                    $('#edit-id').val(t.id);
                    $('#edit-tugas').val(t.tugas);
                    $('#edit-tanggal_mulai').val(formatDateTimeLocal(t.tanggal_mulai));
                    $('#edit-tanggal_selesai').val(formatDateTimeLocal(t.tanggal_selesai));
                    $('#edit-priority').val(t.priority || 'medium');
                    $('#modalEditTask').modal('show');
                    Swal.close();
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Tidak dapat memuat data tugas' });
            }
        });
    });

    // EDIT TASK - SUBMIT
    $('#form-edit-task').on('submit', function (e) {
        e.preventDefault();

        let taskId = $('#edit-id').val();

        // Get current form values
        const currentData = {
            tugas: $('#edit-tugas').val(),
            tanggal_mulai: $('#edit-tanggal_mulai').val(),
            tanggal_selesai: $('#edit-tanggal_selesai').val(),
            priority: $('#edit-priority').val()
        };

        // Check if any data has changed
        const hasDataChanged =
            currentData.tugas !== originalTaskData.tugas ||
            currentData.tanggal_mulai !== originalTaskData.tanggal_mulai ||
            currentData.tanggal_selesai !== originalTaskData.tanggal_selesai ||
            currentData.priority !== originalTaskData.priority;

        // If no changes detected, show message and return
        if (!hasDataChanged) {
            Swal.fire({
                icon: 'info',
                title: 'Tidak Ada Perubahan',
                text: 'Tidak ada data yang diubah. Silakan ubah data terlebih dahulu atau tutup form ini.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        stopAutoRefresh();
        let formData = $(this).serialize();

        const $submitBtn = $(this).find('button[type="submit"]');
        const originalText = $submitBtn.html();
        $submitBtn.html('<span class="spinner-border spinner-border-sm mr-1"></span> Memperbarui...');
        $submitBtn.prop('disabled', true);

        $.ajax({
            url: "/api/tugas/" + taskId,
            type: "PUT",
            data: formData,
            success: function (res) {
                if (res.success) {
                    $('#modalEditTask').modal('hide');
                    loadTasks(true);
                    startAutoRefresh();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2000, showConfirmButton: false });
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON?.errors || {};
                let errorText = '<ul class="list-unstyled text-left">';
                for (const [key, value] of Object.entries(errors)) {
                    errorText += `<li><i class="fas fa-times-circle text-danger mr-1"></i> ${value[0]}</li>`;
                }
                errorText += '</ul>';
                Swal.fire({ icon: 'error', title: 'Validasi Gagal', html: errorText || 'Terjadi kesalahan' });
            },
            complete: function () {
                $submitBtn.html(originalText);
                $submitBtn.prop('disabled', false);
            }
        });
    });

    // TASK DETAIL
    $(document).on('click', '.btn-detail', function () {
        let taskId = $(this).data('id');
        Swal.showLoading();
        $.ajax({
            url: "/api/tugas/" + taskId,
            type: "GET",
            success: function (res) {
                if (res.success) {
                    $('#task-detail-content').html(renderTaskDetail(res.data, {
                        person: res.data.user,
                        personLabel: 'Informasi Karyawan',
                        personBadge: 'Penerima Tugas',
                        personDotClass: 'bg-success',
                        employeeFileLabel: 'Laporan Karyawan'
                    }));
                    $('#modalDetailTask').modal('show');
                    Swal.close();
                    comments.load(taskId);
                }
            },
            error: function () {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Tidak dapat memuat data tugas' });
            }
        });
    });

    // APPROVE TASK
    $(document).on('click', '.btn-approve', function () {
        let taskId = $(this).data('id');
        let taskNama = $(this).data('nama');

        Swal.fire({
            title: 'Setujui Tugas?',
            html: `Tugas dari <strong>${taskNama}</strong> akan ditandai selesai.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                stopAutoRefresh();
                $.ajax({
                    url: "/api/tugas/" + taskId + "/approve",
                    type: "POST",
                    success: function (res) {
                        loadTasks(true);
                        startAutoRefresh();
                        Swal.fire({ icon: 'success', title: 'Disetujui!', text: res.message, timer: 2000, showConfirmButton: false });
                    },
                    error: function (xhr) {
                        startAutoRefresh();
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
                    }
                });
            }
        });
    });

    // REJECT TASK
    $(document).on('click', '.btn-reject', function () {
        let taskId = $(this).data('id');
        let taskNama = $(this).data('nama');
        let taskTugas = $(this).data('tugas');

        $('#reject-task-id').val(taskId);
        $('#reject-task-nama').text(taskNama);
        $('#reject-task-tugas').text(taskTugas);
        $('#reject-catatan').val('');
        $('#modalRejectTask').modal('show');
    });

    $('#form-reject-task').on('submit', function (e) {
        e.preventDefault();
        stopAutoRefresh();
        let taskId = $('#reject-task-id').val();
        let catatan = $('#reject-catatan').val();

        const $submitBtn = $(this).find('button[type="submit"]');
        $submitBtn.prop('disabled', true).html('Menolak...');

        $.ajax({
            url: "/api/tugas/" + taskId + "/reject",
            type: "POST",
            data: { catatan: catatan },
            success: function (res) {
                $('#modalRejectTask').modal('hide');
                loadTasks(true);
                startAutoRefresh();
                Swal.fire({ icon: 'success', title: 'Ditolak!', text: res.message, timer: 2000, showConfirmButton: false });
            },
            error: function (xhr) {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: xhr.responseJSON?.message || 'Terjadi kesalahan' });
            },
            complete: function () {
                $submitBtn.prop('disabled', false).html('Tolak Tugas');
                startAutoRefresh();
            }
        });
    });

    // BULK ACTIONS LOGIC
    let selectedTasks = [];

    function updateBulkActionsBar() {
        if (selectedTasks.length > 0) {
            $('#selected-count').text(selectedTasks.length);
            $('#bulk-actions-bar').fadeIn();
        } else {
            $('#bulk-actions-bar').fadeOut();
        }
    }

    $(document).on('change', '#select-all-tasks', function () {
        let isChecked = $(this).prop('checked');
        $('.task-checkbox').prop('checked', isChecked);
        selectedTasks = [];
        if (isChecked) {
            $('.task-checkbox').each(function () {
                selectedTasks.push({ id: $(this).data('id'), status: $(this).data('status') });
            });
        }
        updateBulkActionsBar();
    });

    $(document).on('change', '.task-checkbox', function () {
        let taskId = $(this).data('id');
        let taskStatus = $(this).data('status');
        if ($(this).prop('checked')) {
            selectedTasks.push({ id: taskId, status: taskStatus });
        } else {
            selectedTasks = selectedTasks.filter(t => t.id !== taskId);
        }
        let total = $('.task-checkbox').length;
        let checked = $('.task-checkbox:checked').length;
        $('#select-all-tasks').prop('checked', total === checked);
        updateBulkActionsBar();
    });

    $('#clear-selection').on('click', function () {
        selectedTasks = [];
        $('.task-checkbox, #select-all-tasks').prop('checked', false);
        updateBulkActionsBar();
    });

    // Implement Bulk Delete as example (others follow similar pattern)
    $('#bulk-delete').on('click', function () {
        if (selectedTasks.length === 0) return;

        Swal.fire({
            title: `Hapus ${selectedTasks.length} Tugas ?`,
            text: 'Tindakan ini tidak dapat dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger px-4 mr-2',
                cancelButton: 'btn btn-outline-dark px-4'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                stopAutoRefresh();
                let promises = selectedTasks.map(t => $.ajax({ url: "/api/tugas/" + t.id, type: "DELETE" }));

                Promise.all(promises).then(() => {
                    selectedTasks = [];
                    $('#select-all-tasks').prop('checked', false);
                    updateBulkActionsBar();
                    loadTasks(true);
                    startAutoRefresh();
                    Swal.fire('Berhasil!', 'Tugas terpilih telah dihapus.', 'success');
                }).catch(() => {
                    startAutoRefresh();
                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                });
            }
        });
    });

    // Toggle Selection Mode - Simplified approach
    // Toggle Selection Mode using DataTables column visibility API
    $('#toggle-selection-mode').on('click', function () {
        const isActive = $(this).hasClass('active');

        if (!isActive) {
            // Activate selection mode
            $(this).addClass('active btn-primary').removeClass('btn-outline-primary');
            $(this).html('<i class="fas fa-times mr-1"></i> Batal');

            // Show checkbox column using DataTables API
            taskTable.column(1).visible(true);

        } else {
            // Deactivate selection mode
            $(this).removeClass('active btn-primary').addClass('btn-outline-primary');
            $(this).html('<i class="far fa-check-square mr-1"></i> Pilih');

            // Hide checkbox column
            taskTable.column(1).visible(false);

            // Clear selections
            selectedTasks = [];
            $('.task-checkbox, #select-all-tasks').prop('checked', false);
            updateBulkActionsBar();
        }

        // Adjust columns after visibility change
        setTimeout(function () {
            taskTable.columns.adjust().draw(false);
        }, 100);
    });

    // Fix DataTables column alignment on sidebar toggle
    $('#sidebarToggle, #sidebarToggleTop').on('click', function () {
        // Wait for sidebar animation to complete
        setTimeout(function () {
            taskTable.columns.adjust().draw(false);
        }, 350);
    });

    // Also adjust on window resize
    $(window).on('resize', function () {
        clearTimeout(window.resizeTimer);
        window.resizeTimer = setTimeout(function () {
            taskTable.columns.adjust().draw(false);
        }, 250);
    });

    startAutoRefresh();
});
