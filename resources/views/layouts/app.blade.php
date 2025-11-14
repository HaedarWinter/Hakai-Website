<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Manajemen Tugas') | Hakai</title>
    <meta name="description" content="@yield('meta_description', 'Sistem Manajemen Tugas Karyawan - Kelola tugas, monitor progress, dan tingkatkan produktivitas tim Anda dengan Hakai.')">
    <meta name="keywords" content="manajemen tugas, task management, produktivitas, karyawan, hakai">
    <meta name="author" content="Hakai">
    <meta name="robots" content="index, follow">
    
    <!-- Security & API -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ session('sanctum_token') }}">
    
    <!-- Preconnect to External Domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Optimized Font Loading with display=swap -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Critical CSS - Load immediately -->
    <link href="{{ asset('sbadmin2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('sbadmin2/css/sb-admin-2.min.css') }}" rel="stylesheet">
    
    <!-- Non-Critical CSS - Load with low priority -->
    <link href="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"></noscript>

    @yield('styles')
</head>

<body id="page-top">

<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-file mr-2"></i>Preview File</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div id="file-preview-container" style="min-height: 500px; max-height: 80vh; overflow: auto;">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a id="download-file-btn" href="#" class="btn btn-primary" download>
                    <i class="fas fa-download mr-1"></i> Download
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Page Wrapper -->
<div id="wrapper">
    @include('layouts.sidebar')

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            @include('layouts.header')

            <!-- Begin Page Content -->
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Hakai 2025 All Right Reserved.</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- PERBAIKAN: Pindahkan Modal Logout ke sini, sebagai anak langsung dari <body> -->
<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Anda yakin ingin keluar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Logout" dibawah ini jika kamu ingin keluar untuk mengakhiri sesi ini.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts - Optimized Loading -->
<!-- Critical scripts loaded normally -->
<script src="{{ asset('sbadmin2/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>

<!-- Non-critical scripts loaded with defer -->
<script src="{{ asset('sbadmin2/vendor/jquery-easing/jquery.easing.min.js') }}" defer></script>
<script src="{{ asset('sbadmin2/js/sb-admin-2.min.js') }}" defer></script>
<script src="{{ asset('sbadmin2/vendor/datatables/jquery.dataTables.min.js') }}" defer></script>
<script src="{{ asset('sbadmin2/vendor/datatables/dataTables.bootstrap4.min.js') }}" defer></script>
<script src="{{ asset('sweetalert2/dist/sweetalert2.all.min.js') }}" defer></script>

<!-- Load Chart.js only when needed -->
@if(View::hasSection('needs-chart'))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
@endif

@yield('scripts')

<script>
    // Setup AJAX headers for API requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + $('meta[name="api-token"]').attr('content')
        }
    });

    // Load pending approval count for admin
    @if(auth()->user()->isAdmin())
    function loadPendingApprovalCount() {
        $.ajax({
            url: '/api/tugas',
            type: 'GET',
            success: function(res) {
                if (res.success) {
                    let pendingCount = res.data.filter(t => t.status === 'submitted').length;
                    if (pendingCount > 0) {
                        $('#pending-approval-badge').text(pendingCount).show();
                    } else {
                        $('#pending-approval-badge').hide();
                    }
                }
            }
        });
    }

    // Load on page load
    $(document).ready(function() {
        loadPendingApprovalCount();
        // Refresh every minute
        setInterval(loadPendingApprovalCount, 60000);
    });
    @endif

    // Handle 401 Unauthorized responses
    $(document).ajaxError(function(event, jqXHR, settings, thrownError) {
        if (jqXHR.status === 401) {
            Swal.fire({
                icon: 'error',
                title: 'Session Expired',
                text: 'Please login again',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '/login';
            });
        }
    });

    // File Preview Function
    window.previewFile = function(fileUrl) {
        $('#filePreviewModal').modal('show');
        $('#download-file-btn').attr('href', fileUrl);
        
        const fileExt = fileUrl.split('.').pop().toLowerCase();
        const container = $('#file-preview-container');
        
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt)) {
            container.html(`<img src="${fileUrl}" class="img-fluid w-100" alt="Preview">`);
        } else if (fileExt === 'pdf') {
            container.html(`<embed src="${fileUrl}" type="application/pdf" width="100%" height="600px">`);
        } else {
            container.html(`
                <div class="text-center py-5">
                    <i class="fas fa-file fa-5x text-muted mb-3"></i>
                    <p class="text-muted">Preview tidak tersedia untuk file ini</p>
                    <a href="${fileUrl}" class="btn btn-primary" download>
                        <i class="fas fa-download mr-1"></i> Download File
                    </a>
                </div>
            `);
        }
    };
</script>

<!-- PERBAIKAN: Satukan penanganan session flash message -->
@session('success')
<script>
    Swal.fire({
        title: "Success!",
        text: "{{ session('success') }}",
        icon: "success"
    });
</script>
@endsession

@session('error')
<script>
    Swal.fire({
        title: "Error!",
        text: "{{ session('error') }}",
        icon: "error"
    });
</script>
@endsession

</body>
</html>
