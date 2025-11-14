@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-check text-info mr-2"></i>
                Detail Tugas
            </h1>
            <div>
                <a href="{{ route('tugas.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50 mr-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Detail Tugas Card -->
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Tugas</h6>
                        <div>
                            @php
                                $status = strtolower(trim($tugas->status));
                                $statusClass = 'secondary';
                                if($status == 'pending') $statusClass = 'warning';
                                elseif($status == 'submitted') $statusClass = 'info';
                                elseif($status == 'rejected') $statusClass = 'danger';
                                elseif($status == 'approved') $statusClass = 'success';
                            @endphp
                            <span class="badge badge-{{ $statusClass }} badge-pill p-2">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600">Nama Karyawan</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $tugas->user ? $tugas->user->nama : 'User tidak ditemukan' }}</div>
                                            <div class="small text-gray-500">{{ $tugas->user ? $tugas->user->email : '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600">Jabatan</h6>
                                    <div>
                                        @if($tugas->user && $tugas->user->jabatan == 'Admin')
                                            <span class="badge badge-success">Admin</span>
                                        @else
                                            <span class="badge badge-warning">Karyawan</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600">Deskripsi Tugas</h6>
                                    <div class="p-3 bg-light rounded">
                                        {{ $tugas->tugas }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600">Periode Tugas</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-info">
                                                <i class="fas fa-calendar-alt text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ date('d F Y', strtotime($tugas->tanggal_mulai)) }}</div>
                                            <div class="small text-gray-500">s/d {{ date('d F Y', strtotime($tugas->tanggal_selesai)) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600">Durasi Tugas</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-warning">
                                                <i class="fas fa-hourglass-half text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">
                                                {{ \Carbon\Carbon::parse($tugas->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($tugas->tanggal_selesai)) }} Hari
                                            </div>
                                            <div class="small text-gray-500">
                                                {{ \Carbon\Carbon::parse($tugas->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tugas->tanggal_selesai)->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h6 class="text-sm text-gray-600">Status Progress</h6>
                                    <div class="progress mb-2" style="height: 10px;">
                                        @if($status == 'pending')
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        @elseif($status == 'submitted')
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        @elseif($status == 'rejected')
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                        @elseif($status == 'approved')
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        @endif
                                    </div>
                                    <div class="small text-gray-500">
                                        @if($status == 'pending')
                                            Menunggu Pengerjaan
                                        @elseif($status == 'submitted')
                                            Menunggu Persetujuan
                                        @elseif($status == 'rejected')
                                            Perlu Revisi
                                        @elseif($status == 'approved')
                                            Tugas Selesai
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan Card -->
                @if($tugas->catatan)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-warning">Catatan</h6>
                        </div>
                        <div class="card-body">
                            <div class="p-3 bg-warning-light rounded">
                                <div class="d-flex">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-sticky-note text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-0">{{ $tugas->catatan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- File & Actions Card -->
            <div class="col-lg-4">
                <!-- File Admin Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-info">File dari Admin</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($tugas->tugas_file)
                            <div class="mb-3">
                                <div class="icon-circle-lg bg-info mx-auto">
                                    <i class="fas fa-file-alt fa-2x text-white"></i>
                                </div>
                            </div>
                            <a href="{{ asset('storage/'.$tugas->tugas_file) }}"
                               class="btn btn-info btn-sm shadow-sm" target="_blank">
                                <i class="fas fa-download mr-1"></i> Unduh File
                            </a>
                            <p class="small text-gray-500 mt-2">
                                {{ pathinfo($tugas->tugas_file, PATHINFO_EXTENSION) }} · {{ number_format(filesize(storage_path('app/public/'.$tugas->tugas_file)) / 1024, 2) }} KB
                            </p>
                        @else
                            <div class="mb-3">
                                <div class="icon-circle-lg bg-light mx-auto">
                                    <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <p class="text-gray-500">Tidak ada file dari admin</p>
                        @endif
                    </div>
                </div>

                <!-- File Karyawan Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-success">File dari Karyawan</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($tugas->karyawan_file)
                            <div class="mb-3">
                                <div class="icon-circle-lg bg-success mx-auto">
                                    <i class="fas fa-file-upload fa-2x text-white"></i>
                                </div>
                            </div>
                            <a href="{{ asset('storage/'.$tugas->karyawan_file) }}"
                               class="btn btn-success btn-sm shadow-sm mb-2" target="_blank">
                                <i class="fas fa-download mr-1"></i> Lihat File
                            </a>

                            @if($tugas->status != 'approved')
                                <div class="mt-3">
                                    <form action="{{ route('tugas.approve', $tugas->id) }}" method="POST" class="d-inline mb-2">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm shadow-sm">
                                            <i class="fas fa-check mr-1"></i> Setujui
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-danger btn-sm shadow-sm" data-toggle="modal" data-target="#rejectModal">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                </div>
                            @endif

                            <p class="small text-gray-500 mt-2">
                                {{ pathinfo($tugas->karyawan_file, PATHINFO_EXTENSION) }} · {{ number_format(filesize(storage_path('app/public/'.$tugas->karyawan_file)) / 1024, 2) }} KB
                            </p>
                        @else
                            <div class="mb-3">
                                <div class="icon-circle-lg bg-light mx-auto">
                                    <i class="fas fa-file-upload fa-2x text-gray-300"></i>
                                </div>
                            </div>
                            <p class="text-gray-500">Karyawan belum mengupload file</p>

                            @if($tugas->status == 'pending' || $tugas->status == 'rejected')
                                <div class="mt-3">
                                    <button class="btn btn-primary btn-sm shadow-sm" disabled>
                                        <i class="fas fa-upload mr-1"></i> Upload File
                                    </button>
                                    <p class="small text-gray-500 mt-2">Hanya karyawan yang dapat upload file</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="card shadow">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Timeline Tugas</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Tugas Dibuat</h6>
                                    <p class="timeline-date">{{ $tugas->created_at->format('d F Y, H:i') }}</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Tanggal Mulai</h6>
                                    <p class="timeline-date">{{ $tugas->tanggal_mulai }}</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Tanggal Selesai</h6>
                                    <p class="timeline-date">{{ $tugas->tanggal_selesai }}</p>
                                </div>
                            </div>

                            @if($tugas->karyawan_file)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">File Diupload</h6>
                                        <p class="timeline-date">{{ $tugas->updated_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($tugas->status == 'approved')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success border-success border-2"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Tugas Disetujui</h6>
                                        <p class="timeline-date">{{ $tugas->updated_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            @elseif($tugas->status == 'rejected')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Tugas Ditolak</h6>
                                        <p class="timeline-date">{{ $tugas->updated_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tolak -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle mr-2"></i> Tolak Tugas
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('tugas.reject', $tugas->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="catatan" class="form-label">
                                <i class="fas fa-comment-alt text-gray-600 mr-1"></i> Catatan Penolakan
                            </label>
                            <textarea name="catatan" id="catatan" class="form-control" rows="3"
                                      placeholder="Berikan alasan penolakan tugas..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-exclamation-circle mr-1"></i> Tolak Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        /* Icon Circle Styling */
        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-circle-lg {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        /* Timeline Styling */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            height: 100%;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 5px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }

        .timeline-content {
            padding-left: 10px;
        }

        .timeline-title {
            font-weight: 600;
            margin-bottom: 2px;
            color: #5a5c69;
        }

        .timeline-date {
            font-size: 0.75rem;
            color: #858796;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.25rem 2rem 0 rgba(58, 59, 69, 0.2);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
        }

        /* Progress Bar Styling */
        .progress {
            height: 10px;
            border-radius: 0.25rem;
            background-color: #eaecf4;
        }

        /* Badge Styling */
        .badge {
            padding: 0.5em 0.75em;
            font-weight: 600;
            border-radius: 0.35rem;
        }

        /* Button Styling */
        .btn {
            border-radius: 0.35rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .modal-dialog-centered {
            min-height: calc(100% - 3.5rem);
        }

        /* Background Colors */
        .bg-warning-light {
            background-color: #fff3cd !important;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            animation: fadeIn 0.5s ease-in-out;
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
    </style>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Tambahkan animasi saat modal dibuka
            $('#rejectModal').on('show.bs.modal', function() {
                // Reset form
                $(this).find('textarea').val('');
            });

            // Tambahkan konfirmasi sebelum menyetujui tugas
            $('form[action*="approve"]').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Setujui Tugas?',
                    text: 'Apakah Anda yakin ingin menyetujui tugas ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, setujui!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    </script>
@endsection
