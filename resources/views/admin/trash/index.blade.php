@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-trash-alt text-danger mr-2"></i>Trash Tugas
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Trash</li>
                </ol>
            </nav>
        </div>

        <div class="premium-card">
            <div class="card-header bg-transparent py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Tugas yang Dihapus</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="trashTable" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tugas</th>
                                <th>Karyawan</th>
                                <th>Dihapus Pada</th>
                                <th>Pemberi Tugas</th>
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

@section('scripts')
    <script>
        $(document).ready(function () {
            let table = $('#trashTable').DataTable({
                processing: true,
                serverSide: false, // Simple fetching for trash
                ajax: {
                    url: "/api/trash",
                    dataSrc: "data"
                },
                columns: [
                    {
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    { data: 'tugas' },
                    {
                        data: 'user',
                        render: (data) => data ? data.nama : 'N/A'
                    },
                    {
                        data: 'deleted_at',
                        render: (data) => new Date(data).toLocaleString('id-ID')
                    },
                    {
                        data: 'creator',
                        render: (data) => data ? data.nama : 'System'
                    },
                    {
                        data: 'id',
                        render: (data) => `
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-success btn-restore" data-id="${data}" title="Restore">
                                                <i class="fas fa-trash-restore"></i>
                                            </button>
                                            <button class="btn btn-danger btn-delete-perm" data-id="${data}" title="Hapus Permanen">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </div>
                                    `
                    }
                ],
                language: {
                    url: "{{ asset('json/datatables-indonesian.json') }}"
                },
                drawCallback: function () {
                    $('.btn-restore').off('click').on('click', function () {
                        let id = $(this).data('id');
                        restoreTask(id);
                    });

                    $('.btn-delete-perm').off('click').on('click', function () {
                        let id = $(this).data('id');
                        deletePermanent(id);
                    });
                }
            });

            function restoreTask(id) {
                Swal.fire({
                    title: 'Kembalikan Tugas?',
                    text: "Tugas ini akan muncul kembali di daftar tugas aktif.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1cc88a',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Ya, Kembalikan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/trash/${id}/restore`,
                            type: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function (res) {
                                if (res.success) {
                                    Swal.fire('Berhasil!', res.message, 'success');
                                    table.ajax.reload();
                                }
                            }
                        });
                    }
                });
            }

            function deletePermanent(id) {
                Swal.fire({
                    title: 'Hapus Permanen?',
                    text: "Data ini tidak akan bisa dikembalikan lagi!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74a3b',
                    cancelButtonColor: '#858796',
                    confirmButtonText: 'Ya, Hapus Permanen!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/trash/${id}`,
                            type: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function (res) {
                                if (res.success) {
                                    Swal.fire('Terhapus!', res.message, 'success');
                                    table.ajax.reload();
                                }
                            }
                        });
                    }
                });
            }
        });
    </script>
@endsection