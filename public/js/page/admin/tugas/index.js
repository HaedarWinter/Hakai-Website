
$(document).ready(function () {
    // Custom File Input Label
    $(".custom-file-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    // Initialize DataTable
    $('head').append('<style>.user-profile-link:hover { background-color: #f8f9fc !important; text-decoration: none; } .user-profile-link:hover .text-primary { text-decoration: underline; }</style>');
    const taskTable = $('#taskTable').DataTable({
        scrollX: true,
        responsive: false, // Turn off responsive to force scrolling
        autoWidth: false, // Respect CSS widths
        pageLength: 10,
        language: {
            url: '/assets/datatables/i18n/id.json'
        },
        columnDefs: [
            { targets: 0, orderable: false, className: 'col-no d-none d-md-table-cell text-center', width: '50px' },
            { targets: 1, visible: false, orderable: false, className: 'col-checkbox', width: '40px' },
            {
                targets: 2,
                className: 'col-karyawan',
                width: '180px',
                // Use data-search attribute for searching instead of rendered HTML
                render: function (data, type, row) {
                    if (type === 'filter') {
                        const div = $(data.trim());
                        return div.attr('data-search') || div.text();
                    }
                    return data;
                }
            },
            { targets: 3, className: 'col-tugas' }, // Auto width
            { targets: 4, className: 'col-periode', width: '170px' },
            { targets: 5, className: 'col-file text-center', width: '100px' },
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
    $('#globalSearchInput').on('keyup', function () {
        taskTable.search(this.value).draw();
    });

    //untuk nomor urut
    taskTable.on('order.dt search.dt draw.dt', function () {
        let i = 1
        taskTable.cells(null, 0, { search: 'applied', order: 'applied' }).every(function () {
            this.data(i++)
        });
    }).draw();

    // --- PERBAIKAN: Manajemen State & Loading yang Lebih Baik ---
    const State = {
        isLoading: false,
        hasError: false,
        lastLoadTime: 0,
        intervalId: null,
    };

    // Variable to store original task data for comparison
    let originalTaskData = {};

    function showLoading() {
        // Only show loading if table is empty to avoid flickering on refresh
        if (taskTable.data().count() === 0) {
            $('#task-body').html(`
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2 text-gray-600">Memuat data tugas...</p>
                    </td>
                </tr>
            `);
        }
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

    // Helper function to format datetime for display
    function formatDateTime(dateString) {
        if (!dateString) return '-';

        try {
            let date;
            if (dateString.includes('T')) {
                date = new Date(dateString);
            } else {
                date = new Date(dateString.replace(' ', 'T'));
            }

            if (isNaN(date.getTime())) return dateString;

            const day = date.getDate();
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');

            return `${day} ${month} ${year}, ${hours}:${minutes}`;
        } catch (error) {
            return dateString;
        }
    }

    // Helper function to format datetime for input datetime-local
    function formatDateTimeLocal(dateString) {
        if (!dateString) return '';

        try {
            let date;
            if (dateString.includes('T')) {
                date = new Date(dateString);
            } else {
                date = new Date(dateString.replace(' ', 'T'));
            }

            if (isNaN(date.getTime())) return '';

            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');

            return `${year}-${month}-${day}T${hours}:${minutes}`;
        } catch (error) {
            return '';
        }
    }

    window.loadTasks = function (forceReload = false) {
        if (State.isLoading && !forceReload) return;
        if (State.hasError && !forceReload) return;

        State.isLoading = true;
        State.hasError = false;
        showLoading();

        $.ajax({
            url: "/api/tugas?per_page=1000&_t=" + Date.now(),
            type: "GET",
            dataType: "json",
            cache: false,
            success: function (res) {
                if (res.success) {
                    taskTable.clear();

                    let stats = { total: 0, selesai: 0, proses: 0, pending: 0 };
                    let dataRows = [];

                    $.each(res.data, function (index, task) {
                        stats.total++;
                        let statusBadge = '';
                        let statusClass = '';
                        let iconClass = '';

                        if (task.status === 'approved') {
                            statusBadge = 'Disetujui'; statusClass = 'success'; iconClass = 'fa-check-circle'; stats.selesai++;
                        } else if (task.status === 'submitted') {
                            statusBadge = 'Menunggu'; statusClass = 'info'; iconClass = 'fa-clock'; stats.proses++;
                        } else if (task.status === 'rejected') {
                            statusBadge = 'Ditolak'; statusClass = 'danger'; iconClass = 'fa-times-circle'; stats.proses++;
                        } else {
                            statusBadge = 'Pending'; statusClass = 'warning'; iconClass = 'fa-hourglass-half'; stats.pending++;
                        }

                        const formattedStartDate = formatDateTime(task.tanggal_mulai);
                        const formattedEndDate = formatDateTime(task.tanggal_selesai);

                        // Premium Action Dropdown
                        let actionButtons = `
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

                        // Checkbox column
                        const checkboxHtml = `
                                                <div class="custom-control custom-checkbox text-center">
                                                    <input type="checkbox" class="custom-control-input task-checkbox"
                                                        id="customCheck${task.id}"
                                                        data-id="${task.id}"
                                                        data-status="${task.status}">
                                                    <label class="custom-control-label" for="customCheck${task.id}"></label>
                                                </div>
                                            `;

                        dataRows.push([
                            index + 1,    // Column 0: No
                            checkboxHtml, // Column 1: Checkbox

                            // Column 2: Employee
                            (() => {
                                const photoUrl = task.user && task.user.profile_photo_path ? `/storage/${task.user.profile_photo_path}` : null;
                                const name = task.user ? task.user.nama : 'N/A';
                                const email = task.user ? task.user.email : '-';
                                const avatarHtml = photoUrl
                                    ? `<img class="img-profile rounded-circle shadow-sm" src="${photoUrl}" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid #fff;">`
                                    : `<div class="icon-circle shadow-sm bg-primary text-white" style="width: 38px; height: 38px;"><i class="fas fa-user"></i></div>`;

                                return `<div class="d-flex align-items-center p-1 user-profile-link" 
                                             style="cursor: pointer; transition: all 0.2s; border-radius: 8px;" 
                                             onclick="window.location.href='/profile/${task.user ? task.user.id : ''}'" 
                                             data-search="${name} ${email}">
                                            <div class="mr-3">${avatarHtml}</div>
                                            <div class="min-width-0">
                                                <div class="font-weight-bold text-primary text-truncate mb-0" style="max-width: 150px;">${name}</div>
                                                <div class="small text-muted text-truncate" style="max-width: 150px;">${email}</div>
                                            </div>
                                        </div>`;
                            })(),

                            `<a href="javascript:void(0)" class="task-link btn-detail" data-id="${task.id}">
                                <div class="task-title font-weight-bold" title="${task.tugas}" style="color: #4e73df;">${task.tugas}</div>
                            </a>`,

                            `<div class="text-center small">
                                <div class="text-nowrap mb-1"><i class="fas fa-calendar-alt text-gray-400 mr-2"></i> ${formattedStartDate}</div>
                                <div class="text-nowrap"><i class="fas fa-calendar-check text-gray-400 mr-2"></i> ${formattedEndDate}</div>
                            </div>`,

                            // Column 5: File Indicator
                            `<div class="d-flex justify-content-center" style="gap: 5px;">
                                ${task.tugas_file ? `<a href="/storage/${task.tugas_file}" target="_blank" class="btn btn-xs btn-outline-primary shadow-sm" title="File Admin"><i class="fas fa-file-export"></i></a>` : ''}
                                ${task.karyawan_file ? `<a href="/storage/${task.karyawan_file}" target="_blank" class="btn btn-xs btn-outline-success shadow-sm" title="File Karyawan"><i class="fas fa-file-import"></i></a>` : ''}
                                ${(!task.tugas_file && !task.karyawan_file) ? '<span class="text-muted small">-</span>' : ''}
                            </div>`,

                            `<span class="status-pill-premium status-pill-${statusClass}"><i class="fas ${iconClass}"></i> ${statusBadge}</span>`,
                            actionButtons
                        ]);

                        // Determine priority for row node attribute later
                        task._priority = task.priority || 'medium';
                    });

                    taskTable.rows.add(dataRows).draw();

                    // Add priority attributes to row nodes for filtering
                    taskTable.rows().every(function (rowIdx) {
                        const data = this.data();
                        const taskObj = res.data[rowIdx];
                        if (taskObj) {
                            $(this.node()).attr('data-priority', taskObj.priority || 'medium');
                        }
                    });

                    $('#total-tugas').text(stats.total);
                    $('#total-selesai').text(stats.selesai);
                    $('#total-proses').text(stats.proses);
                    $('#total-pending').text(stats.pending);
                    State.lastLoadTime = Date.now();
                } else {
                    showError('Server merespon sukses, tetapi tidak ada data.');
                }
            },
            error: function (xhr) {
                let errorMsg = 'Gagal memuat data tugas.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                showError(errorMsg);
            },
            complete: function () {
                State.isLoading = false;
                // Restore selection mode state if active
                if ($('#toggle-selection-mode').hasClass('active')) {
                    $('.selection-col').show();
                }
            }
        });
    }

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
    function applyFilters() {
        let table = $('#taskTable').DataTable();
        table.search('').columns().search('');

        let statusFilter = $('#filter-status').val();
        if (statusFilter) table.column(6).search(statusFilter);

        let userFilter = $('#filter-user').val();
        if (userFilter) table.column(2).search(userFilter);

        table.draw();
    }

    $('#filter-status, #filter-user').on('change', applyFilters);

    $('#filter-priority').on('change', function () {
        let priorityFilter = $(this).val();
        let table = $('#taskTable').DataTable();

        if (priorityFilter) {
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    let row = table.row(dataIndex).node();
                    let priority = $(row).attr('data-priority');
                    return priority === priorityFilter;
                }
            );
        } else {
            // Remove the specific priority filter function
            // Note: This is a bit simplistic if we have multiple custom filters.
            // Ideally we should name the filter or manage the array better.
            // But for now, we'll just clear custom filters when resetting or changing.
            // A better approach is to always have the filter function present but check the value.
            $.fn.dataTable.ext.search.pop();
        }
        table.draw();
    });

    $('#reset-filter').on('click', function () {
        $('#filter-status, #filter-user, #filter-priority').val('');
        $.fn.dataTable.ext.search = [];
        let table = $('#taskTable').DataTable();
        table.search('').columns().search('').draw();
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
                    let t = res.data;
                    // Modern Status Badge
                    let statusBadge = t.status === 'approved' ? `<span class="status-pill-premium status-approved"><i class="fas fa-check-circle mr-1"></i> Disetujui</span>` :
                        t.status === 'submitted' ? `<span class="status-pill-premium status-submitted"><i class="fas fa-hourglass-half mr-1"></i> Menunggu</span>` :
                            t.status === 'rejected' ? `<span class="status-pill-premium status-rejected"><i class="fas fa-times-circle mr-1"></i> Ditolak</span>` :
                                `<span class="status-pill-premium status-pending"><i class="fas fa-clock mr-1"></i> Pending</span>`;

                    // Helper to generate file preview
                    function getFilePreview(fileName, label) {
                        if (!fileName) return '';
                        let fileExt = fileName.split('.').pop().toLowerCase();
                        let filePath = '/storage/' + fileName;
                        let previewId = 'preview-' + Math.random().toString(36).substr(2, 9);

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

                    // Generate Previews
                    let adminPreview = getFilePreview(t.tugas_file, 'Pratinjau Instruksi Admin');
                    let employeePreview = getFilePreview(t.karyawan_file, 'Pratinjau Hasil Karyawan');
                    let filePreview = adminPreview + employeePreview;

                    function getFileCard(fileName, label, colorClass, iconClass) {
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

                    let adminFileCard = getFileCard(t.tugas_file, 'Instruksi Admin', 'primary', 'fas fa-file-alt');
                    let employeeFileCard = getFileCard(t.karyawan_file, 'Laporan Karyawan', 'success', 'fas fa-file-upload');

                    // Modern Detail Template
                    let detailHtml = `
                        <div class="px-4 py-4">
                            <!-- Task Header Info -->
                            <div class="mb-4 pb-3 border-bottom">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary-light text-primary px-2 py-1 mr-2" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">ID: #${t.id}</span>
                                    ${t.priority ? `
                                        <span class="badge badge-${t.priority === 'high' ? 'danger' : t.priority === 'medium' ? 'warning' : 'secondary'}-light text-${t.priority === 'high' ? 'danger' : t.priority === 'medium' ? 'warning' : 'secondary'} px-2 py-1" style="font-size: 0.65rem; font-weight: 800; text-transform: uppercase;">
                                            <i class="fas fa-flag mr-1"></i> Prioritas ${t.priority === 'high' ? 'Tinggi' : t.priority === 'medium' ? 'Sedang' : 'Rendah'}
                                        </span>
                                    ` : ''}
                                </div>
                                <h4 class="font-weight-bold text-dark mb-1" title="${t.tugas}">${t.tugas.length > 100 ? t.tugas.substring(0, 100) + '...' : t.tugas}</h4>
                                <p class="text-xs text-muted mb-0"><i class="far fa-calendar-alt mr-1"></i> Dibuat pada ${formatDateTime(t.created_at)}</p>
                            </div>

                            <!-- Top Section: Karyawan & Status -->
                            <div class="row mb-5">
                                <div class="col-md-7 mb-3 mb-md-0">
                                    <span class="detail-section-label">Informasi Karyawan</span>
                                    <div class="info-card p-3 d-flex align-items-center h-100">
                                        <div class="mr-3 position-relative">
                                            <img class="img-profile rounded-circle shadow-sm border" 
                                                 style="width: 54px; height: 54px; object-fit: cover;"
                                                 src="${t.user.profile_photo_path ? '/storage/' + t.user.profile_photo_path : '/sbadmin2/img/undraw_profile.svg'}"
                                                 alt="Profile">
                                            <div class="position-absolute" style="bottom: -2px; right: -2px;">
                                                <div class="bg-success rounded-circle border border-white" style="width: 12px; height: 12px;"></div>
                                            </div>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="font-weight-bold text-dark text-truncate" style="font-size: 1.05rem;">${t.user.nama}</div>
                                            <div class="small text-muted text-truncate mb-1">${t.user.email}</div>
                                            <span class="badge badge-primary-light text-primary border text-xs px-2 py-1" style="border-radius: 4px;">Penerima Tugas</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <span class="detail-section-label">Periode & Status</span>
                                    <div class="info-card p-3 h-100">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="text-xs font-weight-bold text-muted"><i class="far fa-clock mr-1"></i> Mulai</div>
                                            <div class="small font-weight-bold text-dark">${formatDateTime(t.tanggal_mulai)}</div>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                                            <div class="text-xs font-weight-bold text-muted"><i class="far fa-calendar-check mr-1"></i> Selesai</div>
                                            <div class="small font-weight-bold text-dark">${formatDateTime(t.tanggal_selesai)}</div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <div class="text-xs font-weight-bold text-muted">Status</div>
                                            ${statusBadge}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div class="row mb-5">
                                <div class="col-12">
                                    <span class="detail-section-label">Deskripsi Tugas</span>
                                    <div class="description-box shadow-sm">${(t.tugas ? t.tugas.trim() : '') || '<span class="text-muted italic">Tidak ada deskripsi tugas.</span>'}</div>
                                </div>
                            </div>

                            <!-- Attachments Section -->
                            <div class="row mb-2">
                                <div class="col-12">
                                    <span class="detail-section-label">File Lampiran</span>
                                    <div class="row">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            ${adminFileCard ? adminFileCard : `
                                                <div class="p-3 bg-light rounded text-center text-muted small border-dashed h-100 d-flex align-items-center justify-content-center">
                                                    <div><i class="fas fa-file-circle-exclamation mr-1 opacity-5"></i> Tidak ada file instruksi</div>
                                                </div>
                                            `}
                                        </div>
                                        <div class="col-md-6">
                                            ${employeeFileCard ? employeeFileCard : `
                                                <div class="p-3 bg-light rounded text-center text-muted small border-dashed h-100 d-flex align-items-center justify-content-center">
                                                    <div><i class="fas fa-file-circle-exclamation mr-1 opacity-5"></i> Tidak ada file laporan</div>
                                                </div>
                                            `}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Rejection Note if exists -->
                            ${t.catatan ? `
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="p-3 border-left border-danger bg-danger-light rounded shadow-sm" style="border-left-width: 4px !important;">
                                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Catatan Penolakan</div>
                                        <div class="text-dark small"><i class="fas fa-exclamation-circle mr-2"></i>${t.catatan}</div>
                                    </div>
                                </div>
                            </div>` : ''}

                            <!-- Previews -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    ${filePreview ? `
                                        <div class="preview-section">
                                            <div class="preview-header-wrapper d-flex align-items-center mb-3">
                                                <span class="detail-section-label mb-0 mr-auto">Pratinjau File</span>
                                            </div>
                                            ${filePreview}
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                    $('#task-detail-content').html(detailHtml);
                    $('#modalDetailTask').modal('show');
                    Swal.close();

                    // Load comments
                    loadComments(taskId);
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

    // ==================== COMMENTS FUNCTIONALITY ====================
    let currentTaskId = null;
    let replyingToId = null;

    function loadComments(taskId) {
        currentTaskId = taskId;
        replyingToId = null;
        $('#comments-section').show();
        $('#comments-list').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>');

        $.ajax({
            url: `/api/tugas/${taskId}/comments`,
            type: 'GET',
            cache: false,
            success: function (res) {
                if (res.success) {
                    renderComments(res.data);
                }
            },
            error: function () {
                $('#comments-list').html('<div class="text-center text-danger py-3"><i class="fas fa-exclamation-circle"></i> Gagal memuat komentar</div>');
            }
        });
    }

    function countAllComments(comments) {
        let count = comments.length;
        comments.forEach(c => {
            if (c.replies && c.replies.length > 0) {
                count += countAllComments(c.replies);
            }
        });
        return count;
    }

    function renderComments(comments) {
        const totalCount = countAllComments(comments);
        $('#comments-count').text(totalCount);

        if (comments.length === 0) {
            $('#comments-list').html(`
                <div class="text-center text-muted py-3" id="no-comments">
                    <i class="fas fa-comment-slash fa-2x mb-2"></i>
                    <p class="mb-0">Belum ada komentar</p>
                </div>
            `);
            return;
        }

        let html = renderCommentItems(comments, 0);
        $('#comments-list').html(html);
        $('#comments-list').scrollTop($('#comments-list')[0].scrollHeight);
    }

    function renderCommentItems(comments, depth) {
        let html = '';
        const marginLeft = depth > 0 ? (depth * 20) : 0;
        const bgClass = depth > 0 ? 'comment-reply' : '';

        comments.forEach(function (c) {
            const initial = c.user.nama.charAt(0).toUpperCase();
            const avatarHtml = c.user.photo
                ? `<img src="${c.user.photo}" alt="${c.user.nama}">`
                : initial;

            html += `
                <div class="comment-item ${bgClass}" data-id="${c.id}" style="margin-left: ${marginLeft}px;">
                    <div class="comment-header">
                        <div class="comment-avatar">${avatarHtml}</div>
                        <div class="comment-meta">
                            <div class="comment-author">${c.user.nama}</div>
                            <div class="comment-time">${c.created_at_human}</div>
                        </div>
                        <div class="comment-actions">
                            <button class="btn btn-sm btn-link text-primary btn-reply-comment p-0 mr-2" 
                                    data-id="${c.id}" data-name="${c.user.nama}" title="Balas">
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

            // Render nested replies recursively
            if (c.replies && c.replies.length > 0 && depth < 2) {
                html += renderCommentItems(c.replies, depth + 1);
            }
        });

        return html;
    }

    // Handle reply button click
    $(document).on('click', '.btn-reply-comment', function () {
        const commentId = $(this).data('id');
        const userName = $(this).data('name');
        replyingToId = commentId;

        // Update input placeholder and show indicator
        $('#comment-input')
            .attr('placeholder', `Balas ke ${userName}...`)
            .focus();

        // Add cancel reply button if not already present
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

    // Cancel reply
    $(document).on('click', '#cancel-reply', function () {
        replyingToId = null;
        $('#comment-input').attr('placeholder', 'Tulis komentar...').val('');
        $(this).parent().remove();
    });

    // Add comment form submit (with reply support)
    $('#add-comment-form').on('submit', function (e) {
        e.preventDefault();
        const content = $('#comment-input').val().trim();
        if (!content || !currentTaskId) return;

        const $btn = $('#submit-comment');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        const postData = { content: content };
        if (replyingToId) {
            postData.parent_id = replyingToId;
        }

        $.ajax({
            url: `/api/tugas/${currentTaskId}/comments`,
            type: 'POST',
            data: postData,
            success: function (res) {
                if (res.success) {
                    $('#comment-input').val('').attr('placeholder', 'Tulis komentar...');
                    $('#cancel-reply').parent().remove();
                    replyingToId = null;
                    loadComments(currentTaskId);
                }
            },
            error: function (xhr) {
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

    // Delete comment
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
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/api/tugas/comments/${commentId}`,
                    type: 'DELETE',
                    success: function () {
                        loadComments(currentTaskId);
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Gagal menghapus komentar'
                        });
                    }
                });
            }
        });
    });

    // Reset comments when modal closes
    $('#modalDetailTask').on('hidden.bs.modal', function () {
        currentTaskId = null;
        $('#comments-section').hide();
        $('#comments-list').html('');
        $('#comment-input').val('');
    });

    // Preview Toggle Logic
    $(document).on('click', '.btn-preview-toggle', function () {
        const targetId = $(this).data('toggle-id');
        const $target = $('#' + targetId);

        // Close other previews in this section
        $('.preview-collapse').not($target).removeClass('show');
        $('.btn-preview-toggle').not(this).removeClass('active');

        // Toggle this one
        $target.toggleClass('show');
        $(this).toggleClass('active');
    });

    // Initial Load
    loadTasks();

    startAutoRefresh();
});
