@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-trash-alt text-danger mr-2"></i>
                Sampah Tugas Saya
            </h1>
            <div>
                <a href="{{ route('tugas.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Info Card -->
        <div class="alert alert-info d-flex align-items-center mb-4 border-0 shadow-sm"
            style="border-radius: 12px; background: rgba(54, 185, 204, 0.1); border-left: 4px solid #36b9cc !important;">
            <i class="fas fa-info-circle fa-2x mr-3 text-info"></i>
            <div>
                <strong class="text-info">Tugas di sini akan dihapus permanen secara otomatis setelah 30 hari.</strong><br>
                <span class="small">Anda dapat mengembalikan tugas yang tidak sengaja dihapus sebelum batas waktu
                    tersebut.</span>
            </div>
        </div>

        <!-- Trash Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-trash mr-2"></i> Daftar Tugas Terhapus
                </h6>
                <span class="badge badge-danger badge-counter" id="trash-count">0</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="trashTable" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%">Tugas</th>
                                <th width="15%">Status</th>
                                <th width="20%">Dihapus Pada</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border: none;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .table thead th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
        }

        .badge {
            padding: 0.4em 0.65em;
            font-weight: 600;
            border-radius: 0.35rem;
            font-size: 0.7rem;
        }

        .btn-sm {
            padding: 0.35rem 0.5rem;
            font-size: 0.8rem;
            min-width: 32px;
            height: 32px;
        }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            const trashTable = $('#trashTable').DataTable({
                responsive: true,
                language: {
                    url: "{{ asset('json/datatables-indonesian.json') }}"
                },
                order: [[3, 'desc']]
            });

            // Load trashed tasks
            function loadTrash() {
                $.ajax({
                    url: "/api/tugas/trash/list?per_page=1000",
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    success: function (res) {
                        if (res.success) {
                            trashTable.clear();
                            let count = 0;
                            let dataRows = [];

                            $.each(res.data, function (index, task) {
                                count++;
                                let statusBadge = '';
                                let statusClass = '';

                                if (task.status === 'approved') {
                                    statusBadge = 'Disetujui';
                                    statusClass = 'success';
                                } else if (task.status === 'submitted') {
                                    statusBadge = 'Menunggu';
                                    statusClass = 'info';
                                } else if (task.status === 'rejected') {
                                    statusBadge = 'Ditolak';
                                    statusClass = 'danger';
                                } else {
                                    statusBadge = 'Pending';
                                    statusClass = 'warning';
                                }

                                const deletedAt = new Date(task.deleted_at).toLocaleString('id-ID', {
                                    day: '2-digit',
                                    month: 'short',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });

                                const actionBtns = `
                                                        <button class="btn btn-success btn-sm shadow-sm btn-restore" data-id="${task.id}" title="Kembalikan">
                                                            <i class="fas fa-undo"></i> Kembalikan
                                                        </button>
                                                    `;

                                dataRows.push([
                                    index + 1,
                                    `<div class="font-weight-bold">${task.tugas}</div>`,
                                    `<span class="badge badge-${statusClass}">${statusBadge}</span>`,
                                    `<small>${deletedAt}</small>`,
                                    `<div class="text-center">${actionBtns}</div>`
                                ]);
                            });

                            trashTable.rows.add(dataRows).draw();
                            $('#trash-count').text(count);
                        }
                    },
                    error: function (xhr) {
                        console.error("Error loading trash:", xhr);
                        Swal.fire('Error', 'Gagal memuat data sampah', 'error');
                    }
                });
            }

            // Restore task
            $(document).on('click', '.btn-restore', function () {
                const taskId = $(this).data('id');

                Swal.fire({
                    title: 'Kembalikan Tugas?',
                    text: 'Tugas akan dikembalikan ke daftar utama.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1cc88a',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Ya, Kembalikan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/tugas/${taskId}/restore`,
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                if (res.success) {
                                    Swal.fire('Berhasil!', res.message, 'success');
                                    loadTrash();
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('Error', xhr.responseJSON?.message || 'Gagal mengembalikan tugas', 'error');
                            }
                        });
                    }
                });
            });

            // Initial load
            loadTrash();
        });
    </script>
@endsection