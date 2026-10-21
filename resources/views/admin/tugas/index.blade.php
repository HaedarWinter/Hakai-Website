@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tasks text-primary mr-2"></i>
                Manajemen Tugas
            </h1>
            <div>
                {{-- Desktop Buttons --}}
                <div class="d-none d-md-inline-block">
                    <a href="{{route('tugas.trash')}}" class="btn btn-sm btn-secondary shadow-sm">
                        <i class="fas fa-trash-alt fa-sm text-white-50 mr-1"></i> Sampah
                    </a>
                    <a href="{{route('tugas.excel')}}" data-export-type="Excel"
                        class="btn btn-sm btn-success shadow-sm ml-1">
                        <i class="fas fa-file-excel fa-sm text-white-50 mr-1"></i> Export Excel
                    </a>
                    <a href="{{route('tugas.pdf')}}" data-export-type="PDF" class="btn btn-sm btn-danger shadow-sm ml-1">
                        <i class="fas fa-file-pdf fa-sm text-white-50 mr-1"></i> Export PDF
                    </a>
                </div>

                {{-- Mobile Dropdown Menu --}}
                <div class="dropdown d-md-none mt-2">
                    <button class="btn btn-primary btn-sm dropdown-toggle shadow-sm" type="button" id="dropdownMenuActions"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-cog mr-1"></i> Aksi
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuActions">
                        <a class="dropdown-item" href="{{route('tugas.trash')}}">
                            <i class="fas fa-trash-alt fa-sm fa-fw mr-2 text-gray-400"></i> Sampah
                        </a>
                        <a class="dropdown-item" href="{{route('tugas.excel')}}" data-export-type="Excel">
                            <i class="fas fa-file-excel fa-sm fa-fw mr-2 text-success"></i> Export Excel
                        </a>
                        <a class="dropdown-item" href="{{route('tugas.pdf')}}" data-export-type="PDF">
                            <i class="fas fa-file-pdf fa-sm fa-fw mr-2 text-danger"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <x-stat-card title="Total Tugas" icon="fa-tasks" color="primary" id="total-tugas" />
            <x-stat-card title="Selesai" icon="fa-check-circle" color="success" id="total-selesai" />
            <x-stat-card title="Proses" icon="fa-spinner" color="info" id="total-proses" />
            <x-stat-card title="Pending" icon="fa-clock" color="warning" id="total-pending" />
        </div>

        <!-- Task Table -->
        <div class="premium-card shadow mb-4 animate-in" style="animation-delay: 0.4s">
            <div class="card-header premium-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Data Tugas Karyawan</h6>
                <div>
                    <button class="btn btn-outline-primary btn-sm shadow-sm mr-2" id="toggle-selection-mode"
                        title="Mode Seleksi">
                        <i class="far fa-check-square mr-1"></i> Pilih
                    </button>
                    <button class="btn btn-info btn-sm shadow-sm mr-2" type="button" data-toggle="collapse"
                        data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                    <button class="btn btn-primary btn-sm shadow-sm" data-toggle="modal" data-target="#modalAddTask">
                        <i class="fas fa-plus mr-1"></i> Tambah Tugas
                    </button>
                </div>
            </div>
            <div class="card-body">
                @include('admin.tugas.partials.filter')

                <!-- Bulk Actions -->
                <div id="bulk-actions-bar" class="card bg-gradient-primary text-white mb-3 shadow-lg border-0"
                    style="display: none; border-radius: 0.5rem;">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="mb-2 mb-md-0 font-weight-bold">
                                <i class="fas fa-check-square mr-2"></i> <span id="selected-count">0</span> tugas dipilih
                            </div>
                            <div>
                                <button class="btn btn-light btn-sm mr-1 text-success font-weight-bold shadow-sm"
                                    id="bulk-approve">
                                    <i class="fas fa-check mr-1"></i> Approve
                                </button>
                                <button class="btn btn-light btn-sm mr-1 text-danger font-weight-bold shadow-sm"
                                    id="bulk-reject">
                                    <i class="fas fa-times mr-1"></i> Reject
                                </button>
                                <button class="btn btn-light btn-sm mr-1 text-dark font-weight-bold shadow-sm"
                                    id="bulk-delete">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                                <button class="btn btn-outline-light btn-sm" id="clear-selection">
                                    <i class="fas fa-times-circle mr-1"></i> Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="taskTable" width="100%" cellspacing="0">
                        <thead>
                            <tr class="table-premium-header">
                                <th class="text-center col-no d-none d-md-table-cell" style="width: 50px;">No</th>
                                <th class="text-center col-checkbox" style="width: 40px;">
                                    <div class="custom-control custom-checkbox d-inline-block">
                                        <input type="checkbox" class="custom-control-input" id="select-all-tasks">
                                        <label class="custom-control-label" for="select-all-tasks"></label>
                                    </div>
                                </th>
                                <th class="col-karyawan" style="width: 180px;">Karyawan</th>
                                <th class="col-tugas" style="min-width: 200px;">Tugas</th>
                                <th class="text-center col-periode" style="width: 170px;">Periode</th>
                                <th class="text-center col-file" style="width: 100px;">File</th>
                                <th class="text-center col-status" style="width: 130px;">Status</th>
                                <th class="text-center col-aksi" style="width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="task-body">
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('admin.tugas.partials.modals.add')
    @include('admin.tugas.partials.modals.edit')
    @include('admin.tugas.partials.modals.detail')
    @include('admin.tugas.partials.modals.reject')

@endsection

@section('styles')
    <style>
        /* Card Styling */
        .premium-card {
            border: none;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-card:hover {
            transform: translateY(-5px);
        }

        /* Table Styling */
        .table thead th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            white-space: nowrap;
            vertical-align: middle;
            padding: 0.75rem 0.5rem;
        }

        /* Fix table column widths for consistent alignment */
        #taskTable {
            table-layout: fixed !important;
            width: 100% !important;
        }

        /* Table cells vertical alignment */
        #taskTable tbody td {
            vertical-align: middle !important;
            padding: 0.6rem 0.5rem !important;
        }

        /* Column Widths */
        .col-no {
            width: 50px !important;
            text-align: center !important;
        }

        .col-checkbox {
            width: 40px !important;
            text-align: center !important;
        }

        .col-checkbox .custom-control {
            display: inline-block !important;
            padding-left: 1.5rem;
            margin: 0 auto;
        }

        .col-karyawan {
            width: 180px !important;
            white-space: nowrap;
        }

        .col-tugas {
            min-width: 200px !important;
            white-space: nowrap !important;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .task-title {
            white-space: nowrap !important;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
            width: 100%;
            color: #4e73df;
            font-weight: 500;
        }

        .task-link:hover .task-title {
            text-decoration: underline;
            color: #224abe;
        }

        .col-periode {
            width: 170px !important;
            text-align: center !important;
            white-space: nowrap;
        }

        .col-file {
            width: 100px !important;
            text-align: center !important;
            white-space: nowrap;
        }

        .col-status {
            width: 130px !important;
            text-align: center !important;
            white-space: nowrap;
        }

        .col-aksi {
            width: 200px !important;
            text-align: center !important;
            white-space: nowrap;
        }

        /* Action Buttons Container - Improved Layout */
        .action-buttons-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        /* Standardize all action buttons with better proportions */
        .action-buttons-container .btn {
            padding: 0.35rem 0.5rem !important;
            font-size: 0.8rem !important;
            line-height: 1 !important;
            border-radius: 0.25rem !important;
            min-width: 32px !important;
            height: 32px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            font-weight: 500 !important;
            border-width: 1px !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08) !important;
            transition: all 0.15s ease !important;
        }

        /* Improved button colors for better balance */
        .action-buttons-container .btn-success {
            background-color: #1cc88a !important;
            border-color: #1cc88a !important;
            color: #fff !important;
        }

        .action-buttons-container .btn-success:hover {
            background-color: #17a673 !important;
            border-color: #17a673 !important;
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(28, 200, 138, 0.3) !important;
        }

        .action-buttons-container .btn-danger {
            background-color: #e74a3b !important;
            border-color: #e74a3b !important;
            color: #fff !important;
        }

        .action-buttons-container .btn-danger:hover {
            background-color: #d32f2f !important;
            border-color: #d32f2f !important;
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(231, 74, 59, 0.3) !important;
        }

        .action-buttons-container .btn-info {
            background-color: #36b9cc !important;
            border-color: #36b9cc !important;
            color: #fff !important;
        }

        .action-buttons-container .btn-info:hover {
            background-color: #2c9faf !important;
            border-color: #2c9faf !important;
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(54, 185, 204, 0.3) !important;
        }

        .action-buttons-container .btn-secondary {
            background-color: #858796 !important;
            border-color: #858796 !important;
            color: #fff !important;
        }

        .action-buttons-container .btn-secondary:hover {
            background-color: #6e707e !important;
            border-color: #6e707e !important;
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(133, 135, 150, 0.3) !important;
        }

        /* Fix three-dot dropdown button */
        .action-buttons-container .dropdown-toggle {
            padding: 0.35rem 0.45rem !important;
            min-width: 32px !important;
        }

        .action-buttons-container .dropdown-toggle::after {
            display: none !important;
        }

        /* Remove Bootstrap btn-group spacing issues */
        .action-buttons-container .btn-group {
            margin: 0 !important;
        }

        /* FIX: Reset dropdown-item styling to prevent button appearance */
        .action-buttons-container .dropdown-menu .dropdown-item {
            display: block !important;
            width: 100% !important;
            padding: 0.5rem 1rem !important;
            clear: both !important;
            font-weight: 400 !important;
            color: #3a3b45 !important;
            text-align: inherit !important;
            white-space: nowrap !important;
            background-color: transparent !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            min-width: auto !important;
            height: auto !important;
            transform: none !important;
        }

        .action-buttons-container .dropdown-menu .dropdown-item:hover {
            background-color: #f8f9fc !important;
            color: #3a3b45 !important;
            transform: none !important;
        }

        .action-buttons-container .dropdown-menu {
            min-width: 10rem;
            padding: 0.5rem 0;
            margin: 0;
            font-size: 0.85rem;
            border: 1px solid rgba(0, 0, 0, .1);
            border-radius: 0.35rem;
        }

        /* Button Styling */
        .btn {
            border-radius: 0.35rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Badge Styling - Improved */
        .badge {
            padding: 0.4em 0.65em;
            font-weight: 600;
            border-radius: 0.35rem;
            font-size: 0.7rem;
            display: inline-block;
            white-space: nowrap;
        }

        /* Status badge specific styling */
        .badge.px-3 {
            padding-left: 0.6rem !important;
            padding-right: 0.6rem !important;
        }

        .badge.py-2 {
            padding-top: 0.35rem !important;
            padding-bottom: 0.35rem !important;
        }

        /* Icon Circle */
        .icon-circle {
            width: 2.2rem;
            height: 2.2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Custom File Input */
        .custom-file-label::after {
            content: "Browse";
        }

        /* Fix row highlighting - remove unintentional brightness */
        #taskTable tbody tr {
            background-color: #fff !important;
            transition: background-color 0.2s ease;
        }

        #taskTable tbody tr:hover {
            background-color: #f8f9fc !important;
        }

        /* Periode column styling */
        .text-nowrap {
            white-space: nowrap !important;
        }

        /* Employee info styling */
        .col-karyawan .d-flex {
            align-items: center;
        }

        .col-karyawan .font-weight-bold {
            font-size: 0.85rem;
            line-height: 1.3;
        }

        .col-karyawan .small {
            font-size: 0.75rem;
            color: #858796 !important;
        }

        /* Responsive Table Fixes */
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 1200px) {
            .action-buttons-container .btn {
                min-width: 30px !important;
                height: 30px !important;
                padding: 0.3rem 0.4rem !important;
                font-size: 0.75rem !important;
            }
        }

        @media (max-width: 992px) {
            .action-buttons-container {
                gap: 3px;
            }

            .action-buttons-container .btn {
                min-width: 28px !important;
                height: 28px !important;
                padding: 0.25rem 0.35rem !important;
                font-size: 0.7rem !important;
            }

            .badge {
                font-size: 0.65rem;
                padding: 0.3em 0.5em;
            }
        }

        @media (max-width: 768px) {

            #taskTable th,
            #taskTable td {
                padding: 0.5rem 0.35rem !important;
            }

            .action-buttons-container {
                flex-wrap: nowrap;
                gap: 2px;
            }

            .action-buttons-container .btn {
                min-width: 26px !important;
                height: 26px !important;
                padding: 0.2rem 0.3rem !important;
                font-size: 0.65rem !important;
            }

            .icon-circle {
                width: 1.8rem;
                height: 1.8rem;
            }

            .col-karyawan .font-weight-bold {
                font-size: 0.8rem;
            }

            .col-karyawan .small {
                font-size: 0.7rem;
            }
        }

        /* DataTables Responsive Fix */
        .dataTables_wrapper {
            width: 100%;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        /* Fix DataTables pagination */
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1rem;
        }

        .bg-gray-100 {
            background-color: #f8f9fc !important;
        }

        /* Pagination Disabled Cursor */
        .paginate_button.disabled a,
        .paginate_button.disabled,
        .page-item.disabled .page-link,
        .page-item.disabled {
            cursor: not-allowed !important;
            pointer-events: all !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="{{ asset('js/export-handler.js') }}"></script>
    <script src="{{ asset('js/page/admin/tugas/index.js') }}"></script>
@endsection