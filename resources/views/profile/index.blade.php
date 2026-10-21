@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Hero Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="premium-card shadow border-0 overflow-hidden animate-in">
                    <!-- Cover Image (Gradient) -->
                    <div class="bg-gradient-primary"
                        style="height: 150px; background: linear-gradient(45deg, #4e73df 0%, #224abe 100%);"></div>

                    <div class="card-body position-relative pt-0 pb-4">
                        <div class="d-sm-flex align-items-end">
                            <!-- Profile Photo -->
                            <div class="position-relative mr-4" style="top: -50px; margin-bottom: -50px;">
                                <img class="img-profile rounded-circle shadow-lg border-white"
                                    style="width: 140px; height: 140px; object-fit: cover; border: 4px solid #fff;"
                                    src="{{ $user->profile_photo_url ?? asset('sbadmin2/img/undraw_profile.svg') }}"
                                    id="profileImageDisplay" alt="Profile Photo">

                                <!-- Photo Edit Button (Own Profile Only) -->
                                @if($isOwnProfile)
                                    <button type="button" class="btn btn-primary rounded-circle shadow-sm position-absolute"
                                        id="trigger-photo-upload"
                                        style="bottom: 10px; right: 0; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
                                        title="Ganti Foto">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                @endif
                            </div>

                            <!-- User Info -->
                            <div class="mt-4 flex-grow-1">
                                <h1 class="h3 text-gray-900 font-weight-bold mb-1">{{ $user->nama }}</h1>
                                <div class="d-flex align-items-center mb-2 flex-wrap" style="gap: 8px;">
                                    <span class="status-pill-premium status-pill-{{ $user->jabatan == 'Admin' ? 'success' : 'warning' }}">
                                        <i class="fas {{ $user->jabatan == 'Admin' ? 'fa-shield-alt' : 'fa-user' }} mr-1"></i>
                                        {{ ucfirst($user->jabatan) }}
                                    </span>
                                    <span class="glass-pill px-3 py-1 text-gray-600 small">
                                        <i class="fas fa-calendar-alt mr-1"></i> Bergabung:
                                        {{ $user->tanggal_bergabung ? \Carbon\Carbon::parse($user->tanggal_bergabung)->translatedFormat('d F Y') : 'N/A' }}
                                    </span>
                                </div>
                                @if($user->bio)
                                    <p class="text-gray-600 mb-0 small" style="max-width: 600px;">{{ $user->bio }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Tabs for Mobile/Desktop -->
            <div class="col-lg-3 mb-4">
                <div class="premium-card shadow mb-4 animate-in" style="animation-delay: 0.1s">
                    <div class="card-body p-2">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active font-weight-bold py-3" id="v-pills-overview-tab" data-toggle="pill"
                                href="#v-pills-overview" role="tab" aria-controls="v-pills-overview" aria-selected="true">
                                <i class="fas fa-chart-pie mr-2 text-primary" style="width: 20px;"></i> Ringkasan
                            </a>
                            <a class="nav-link font-weight-bold py-3" id="v-pills-biodata-tab" data-toggle="pill"
                                href="#v-pills-biodata" role="tab" aria-controls="v-pills-biodata" aria-selected="false">
                                <i class="fas fa-id-card mr-2 text-info" style="width: 20px;"></i>
                                {{ $isOwnProfile ? 'Edit Biodata' : 'Detail Biodata' }}
                            </a>
                            @if($isOwnProfile)
                                <a class="nav-link font-weight-bold py-3" id="v-pills-security-tab" data-toggle="pill"
                                    href="#v-pills-security" role="tab" aria-controls="v-pills-security" aria-selected="false">
                                    <i class="fas fa-lock mr-2 text-danger" style="width: 20px;"></i> Keamanan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            <!-- Contact Card -->
            <div class="premium-card shadow mb-4 animate-in" style="animation-delay: 0.2s">
                <div class="card-header premium-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Kontak</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-uppercase text-gray-500 font-weight-bold">Email</small>
                        <div class="font-weight-bold text-dark">{{ $user->email }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-uppercase text-gray-500 font-weight-bold">No HP</small>
                        <div class="font-weight-bold text-dark">{{ $user->no_hp ?? '-' }}</div>
                    </div>
                    <div>
                        <small class="text-uppercase text-gray-500 font-weight-bold">Alamat</small>
                        <div class="font-weight-bold text-dark">{{ $user->alamat ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Team Members Sidebar -->
            <div class="premium-card shadow mb-4 animate-in" style="animation-delay: 0.3s">
                <div class="card-header premium-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Rekan Kerja</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($otherUsers as $colleague)
                        <a href="{{ route('profile.show', $colleague->id) }}" class="list-group-item list-group-item-action d-flex align-items-center border-0 mb-1 rounded">
                            <div class="mr-3">
                                <img class="img-profile rounded-circle" 
                                     src="{{ $colleague->profile_photo_url ?? asset('sbadmin2/img/undraw_profile.svg') }}" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            </div>
                            <div class="w-100">
                                <span class="font-weight-bold text-dark d-block text-truncate" style="max-width: 150px;">
                                    {{ $colleague->nama }}
                                </span>
                                <small class="text-muted">{{ ucfirst($colleague->jabatan) }}</small>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 fa-sm"></i>
                        </a>
                        @endforeach
                        @if($otherUsers->isEmpty())
                            <div class="text-center p-3 text-muted">Tidak ada user lain.</div>
                        @endif
                    </div>
                </div>
            </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9">
                <div class="tab-content" id="v-pills-tabContent">

                    <!-- OVERVIEW TAB -->
                    <div class="tab-pane fade show active" id="v-pills-overview" role="tabpanel"
                        aria-labelledby="v-pills-overview-tab">

                        <!-- Stats Grid -->
                        <div class="row">
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="premium-card h-100 glow-success">
                                    <div class="stat-card-v2">
                                        <div class="stat-icon-box bg-success shadow-sm text-white">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-label text-success">Tugas Selesai</div>
                                            <div class="stat-value">{{ $completedTasks }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="premium-card h-100 glow-warning">
                                    <div class="stat-card-v2">
                                        <div class="stat-icon-box bg-warning shadow-sm text-white">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="stat-info">
                                            <div class="stat-label text-warning">Tugas Pending</div>
                                            <div class="stat-value">{{ $pendingTasks }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="premium-card h-100 glow-info">
                                    <div class="stat-card-v2">
                                        <div class="stat-icon-box bg-info shadow-sm text-white">
                                            <i class="fas fa-tachometer-alt"></i>
                                        </div>
                                        <div class="stat-info w-100">
                                            <div class="stat-label text-info">Efisiensi</div>
                                            <div class="d-flex align-items-center">
                                                <div class="stat-value mr-3">{{ $efficiency }}%</div>
                                                <div class="progress progress-sm flex-grow-1" style="height: 6px;">
                                                    <div class="progress-bar bg-info" role="progressbar"
                                                        style="width: {{ $efficiency }}%"
                                                        aria-valuenow="{{ $efficiency }}" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="premium-card shadow mb-4 animate-in" style="animation-delay: 0.5s">
                            <div class="card-header premium-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terakhir</h6>
                            </div>
                            <div class="card-body">
                                @forelse($recentActivities as $activity)
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom-light">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-tasks text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">{{ $activity->created_at->diffForHumans() }}</div>
                                            <span class="font-weight-bold">{{ $activity->tugas }}</span>
                                            <div class="small text-muted">Status: {{ ucfirst($activity->status) }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">Belum ada aktivitas tercatat.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- BIODATA TAB -->
                    <div class="tab-pane fade" id="v-pills-biodata" role="tabpanel" aria-labelledby="v-pills-biodata-tab">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    {{ $isOwnProfile ? 'Edit Biodata' : 'Detail Biodata' }}</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Hidden Photo Input -->
                                    <input type="file" id="photo" name="photo" class="d-none" accept="image/*" {{ !$isOwnProfile ? 'disabled' : '' }}>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold">Nama Lengkap</label>
                                            <input type="text" name="nama" class="form-control" value="{{ $user->nama }}" {{ !$isOwnProfile ? 'readonly' : 'required' }}>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                                {{ !$isOwnProfile ? 'readonly' : 'required' }}>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold">No. HP / WhatsApp</label>
                                            <input type="text" name="no_hp" class="form-control" value="{{ $user->no_hp }}"
                                                placeholder="{{ $isOwnProfile ? '08xxxxxxxxxx' : '-' }}" {{ !$isOwnProfile ? 'readonly' : '' }}>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="font-weight-bold">Tanggal Bergabung</label>
                                            <input type="date" name="tanggal_bergabung" class="form-control"
                                                value="{{ $user->tanggal_bergabung }}" {{ !$isOwnProfile ? 'readonly' : '' }}>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Alamat Lengkap</label>
                                        <textarea name="alamat" class="form-control" rows="2" {{ !$isOwnProfile ? 'readonly' : '' }}>{{ $user->alamat }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Bio Singkat</label>
                                        <textarea name="bio" class="form-control" rows="3"
                                            placeholder="{{ $isOwnProfile ? 'Ceritakan sedikit tentang diri Anda...' : '-' }}"
                                            {{ !$isOwnProfile ? 'readonly' : '' }}>{{ $user->bio }}</textarea>
                                    </div>

                                    @if($isOwnProfile)
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- SECURITY TAB -->
                    <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-danger">Keamanan Akun</h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('profile.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label class="font-weight-bold">Password Saat Ini</label>
                                        <input type="password" name="current_password" class="form-control">
                                        @error('current_password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Password Baru</label>
                                        <input type="password" name="password" class="form-control">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-bold">Konfirmasi Password Baru</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>

                                    <div class="alert alert-warning small">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Pastikan password Anda kuat (minimal 8 karakter).
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-danger px-4">
                                            <i class="fas fa-key mr-1"></i> Ganti Password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Cropper Modal (Reused) -->
    <div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-crop mr-2"></i>Potong Foto Profil</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="img-container" style="max-height: 500px;">
                        <img id="cropperImage" src="" alt="Image for cropping" style="max-width: 100%;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="cropAndSave">Potong & Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <style>
        .nav-pills .nav-link {
            border-radius: 0.5rem;
            transition: all 0.2s;
            color: #5a5c69;
        }

        .nav-pills .nav-link:hover {
            background-color: #eaecf4;
        }

        .nav-pills .nav-link.active {
            background-color: #4e73df;
            color: #fff;
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.25);
        }

        .nav-pills .nav-link.active i {
            color: #fff !important;
        }

        .border-bottom-light {
            border-bottom: 1px solid #e3e6f0;
        }

        .border-bottom-light:last-child {
            border-bottom: none;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        // ... Cropper Logic (Similar to Settings, simplified) ...
        let cropper;
        $("#trigger-photo-upload").on("click", function () { $("#photo").click(); });

        $("#photo").on("change", function (e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    $('#cropperImage').attr('src', event.target.result);
                    $('#cropperModal').modal('show');
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        $('#cropperModal').on('shown.bs.modal', function () {
            cropper = new Cropper(document.getElementById('cropperImage'), { aspectRatio: 1, viewMode: 2 });
        }).on('hidden.bs.modal', function () {
            if (cropper) { cropper.destroy(); cropper = null; }
        });

        $('#cropAndSave').on('click', function () {
            if (cropper) {
                cropper.getCroppedCanvas({ width: 400, height: 400 }).toBlob(function (blob) {
                    const url = URL.createObjectURL(blob);
                    $('#profileImageDisplay').attr('src', url);

                    // Set file to input
                    const file = new File([blob], "profile.jpg", { type: "image/jpeg" });
                    const dt = new DataTransfer(); dt.items.add(file);
                    document.getElementById('photo').files = dt.files;

                    $('#cropperModal').modal('hide');
                });
            }
        });
    </script>
@endsection