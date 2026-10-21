@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">
            <i class="fas fa-cogs text-primary mr-2"></i> Pengaturan Akun
        </h1>

        <div class="row">
            <!-- Profile Settings -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Edit Profil</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf


                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    <img class="img-profile rounded-circle mb-2"
                                        style="width: 100px; height: 100px; object-fit: cover;"
                                        src="{{ auth()->user()->profile_photo_url ?? asset('sbadmin2/img/undraw_profile.svg') }}"
                                        alt="Profile Photo">
                                    <button type="button" class="btn btn-sm btn-primary rounded-circle position-absolute"
                                        id="trigger-photo-upload"
                                        style="bottom: 10px; right: 0; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; border: 2px solid white;"
                                        title="Ganti Foto Profil">
                                        <i class="fas fa-pen fa-xs"></i>
                                    </button>
                                </div>
                                <div class="small text-muted mt-2">Format: JPG, PNG (Max 2MB)</div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama', $user->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Foto Profil</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('photo') is-invalid @enderror"
                                        id="photo" name="photo" accept="image/*">
                                    <label class="custom-file-label" for="photo">Pilih foto...</label>
                                </div>
                                @error('photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Password Settings -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger">Ganti Password</h6>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <form action="{{ route('settings.password') }}" method="POST"
                            class="flex-grow-1 d-flex flex-column">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="font-weight-bold">Password Saat Ini</label>
                                <input type="password" name="current_password"
                                    class="form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}">

                                @error('current_password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Password Baru</label>
                                <input type="password" name="password"
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">

                                @error('password')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}">

                                @error('password_confirmation')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mt-auto">
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-key mr-1"></i> Ganti Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="cropperModalLabel">
                        <i class="fas fa-crop mr-2"></i>Potong Foto Profil
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container" style="max-height: 500px;">
                        <img id="cropperImage" src="" alt="Image for cropping" style="max-width: 100%;">
                    </div>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle mr-1"></i>
                            Geser dan atur area yang ingin dipotong. Foto akan dipotong menjadi persegi (1:1).
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="cropAndSave">
                        <i class="fas fa-check mr-1"></i>Potong & Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script>
        let cropper;
        let croppedImageBlob;

        // Custom File Input Label and Cropper Trigger
        $("#photo").on("change", function (e) {
            const file = e.target.files[0];
            if (file) {
                // Update label
                $(this).siblings(".custom-file-label").addClass("selected").html(file.name);

                // Show cropping modal
                const reader = new FileReader();
                reader.onload = function (event) {
                    $('#cropperImage').attr('src', event.target.result);
                    $('#cropperModal').modal('show');

                    // Initialize cropper after modal is shown
                    $('#cropperModal').on('shown.bs.modal', function () {
                        if (cropper) {
                            cropper.destroy();
                        }
                        cropper = new Cropper(document.getElementById('cropperImage'), {
                            aspectRatio: 1,
                            viewMode: 2,
                            autoCropArea: 1,
                            responsive: true,
                            restore: false,
                            guides: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                        });
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        // Crop and save
        $('#cropAndSave').on('click', function () {
            if (cropper) {
                // Get cropped canvas
                const canvas = cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                // Convert to blob
                canvas.toBlob(function (blob) {
                    croppedImageBlob = blob;

                    // Show preview
                    const url = URL.createObjectURL(blob);
                    $('.img-profile').attr('src', url);

                    // Create a new File object from blob
                    const file = new File([blob], "profile-photo.jpg", { type: "image/jpeg" });

                    // Create new FileList
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    document.getElementById('photo').files = dataTransfer.files;

                    $('#cropperModal').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Foto dipotong!',
                        text: 'Silakan klik "Simpan Perubahan" untuk menyimpan foto profil baru.',
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }, 'image/jpeg', 0.95);
            }
        });

        // Clean up cropper when modal is hidden
        $('#cropperModal').on('hidden.bs.modal', function () {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        // Prevent unnecessary form submissions (FIXED: Exclude logout form)
        $(document).ready(function () {
            // Only apply to forms on this page, not the logout form in the modal
            $('form[action="{{ route('settings.update') }}"], form[action="{{ route('settings.password') }}"]').each(function () {
                const $form = $(this);
                const initialData = $form.serialize();

                $form.on('submit', function (e) {
                    const currentData = $form.serialize();
                    const hasFile = $form.find('input[type="file"]').filter(function () {
                        return this.value !== '';
                    }).length > 0;

                    if (initialData === currentData && !hasFile) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'info',
                            title: 'Tidak ada perubahan',
                            text: 'Anda belum melakukan perubahan apapun.',
                            timer: 2000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                });
            });
        });

        // Trigger file input when pen icon is clicked
        $('#trigger-photo-upload').on('click', function () {
            $('#photo').click();
        });
    </script>
@endsection