@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-history text-primary mr-2"></i>Audit Log
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Audit Log</li>
                </ol>
            </nav>
        </div>

        <div class="premium-card">
            <div class="card-header bg-transparent py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Aktivitas Sistem</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover" id="auditTable" width="100%" cellspacing="0">
                        <thead class="bg-light">
                            <tr>
                                <th width="15%">Waktu</th>
                                <th width="15%">User</th>
                                <th width="10%">Aksi</th>
                                <th>Deskripsi</th>
                                <th width="10%">Tipe Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX with Pagination -->
                        </tbody>
                    </table>
                </div>
                <div id="auditPagination" class="mt-4 d-flex justify-content-end"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            let currentPage = 1;

            function loadAuditLogs(page = 1) {
                currentPage = page;
                $.ajax({
                    url: `/api/audit-logs?page=${page}`,
                    type: 'GET',
                    success: function (res) {
                        if (res.success) {
                            renderTable(res.data.data);
                            renderPagination(res.data);
                        }
                    }
                });
            }

            function renderTable(logs) {
                let html = '';
                logs.forEach(log => {
                    const date = new Date(log.created_at).toLocaleString('id-ID');
                    const actionBadge = getActionBadge(log.action);

                    html += `
                        <tr>
                            <td>${date}</td>
                            <td>
                                <div class="font-weight-bold">${log.user ? log.user.nama : 'System'}</div>
                                <small class="text-muted">${log.user ? log.user.email : '-'}</small>
                            </td>
                            <td class="text-center">${actionBadge}</td>
                            <td>${log.description}</td>
                            <td><span class="badge badge-light text-uppercase">${log.loggable_type ? log.loggable_type.split('\\').pop() : '-'}</span></td>
                        </tr>
                    `;
                });
                $('#auditTable tbody').html(html);
            }

            function getActionBadge(action) {
                let cls = 'secondary';
                switch (action.toLowerCase()) {
                    case 'created': cls = 'success'; break;
                    case 'updated': cls = 'info'; break;
                    case 'deleted': cls = 'danger'; break;
                    case 'approved': cls = 'success'; break;
                    case 'rejected': cls = 'warning'; break;
                    case 'trashed': cls = 'warning'; break;
                    case 'restored': cls = 'primary'; break;
                }
                return `<span class="badge badge-${cls} w-100">${action.toUpperCase()}</span>`;
            }

            function renderPagination(data) {
                let html = '<ul class="pagination pagination-sm mb-0">';

                // Previous
                html += `<li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${data.current_page - 1}">Previous</a>
                </li>`;

                // Pages (Limited show)
                for (let i = 1; i <= data.last_page; i++) {
                    if (i === 1 || i === data.last_page || (i >= data.current_page - 2 && i <= data.current_page + 2)) {
                        html += `<li class="page-item ${data.current_page === i ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>`;
                    } else if (i === data.current_page - 3 || i === data.current_page + 3) {
                        html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }

                // Next
                html += `<li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${data.current_page + 1}">Next</a>
                </li>`;

                html += '</ul>';
                $('#auditPagination').html(html);

                $('.page-link').on('click', function (e) {
                    e.preventDefault();
                    let page = $(this).data('page');
                    if (page) loadAuditLogs(page);
                });
            }

            // Initial load
            loadAuditLogs();
        });
    </script>
@endsection