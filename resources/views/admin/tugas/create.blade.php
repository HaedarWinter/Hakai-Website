@extends('layouts.app')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-plus"></i>
        {{ $title }}
    </h1>

    {{-- Notifikasi --}}
    <div id="notification-container"></div>

    <div class="card">
        <!-- Card Header -->
        <div class="card-header bg-primary d-flex flex-wrap py-3">
            <div class="mb-1 mr-2">
                <a href="{{ route('tugas.index') }}" class="btn btn-sm border-5 btn-outline-light">
                    <i class="fas fa-arrow-circle-left mr-1"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <!-- PERBAIKAN: TAMBAHKAN ACTION, METHOD, DAN ENCTYPE -->
            <form action="{{ route('tugas.store') }}" method="POST" enctype="multipart/form-data" id="form-tugas">
                @csrf
                <div class="row">
                    <!-- Insert Column Nama -->
                    <div class="col-12 mb-3">
                        <label for="user_id"><span class="text-danger">*</span> Nama :</label>
                        <select name="user_id" id="user_id"
                                class="form-control">
                            <option selected disabled>-- Pilih Nama --</option>
                            @foreach($user as $item)
                                <option value="{{$item->id}}" {{ old('user_id') == $item->id ? 'selected' : '' }}>{{$item->nama}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="user_id-error"></div>
                    </div>

                    <!-- Nama Tugas -->
                    <div class="col-md-12 col-12 mb-3">
                        <label><span class="text-danger">*</span> Tugas :</label>
                        <textarea name="tugas" id="tugas" rows="1"
                                  class="form-control"
                                  oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ old('tugas') }}</textarea>
                        <div class="invalid-feedback" id="tugas-error"></div>
                    </div>

                    <!-- Upload File -->
                    <div class="col-md-12 col-12 mb-3">
                        <label for="file">Upload File (opsional):</label>
                        <input type="file" name="file" id="file"
                               class="form-control-file"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                        <small class="form-text text-muted">Format yang diizinkan: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</small>
                        <div class="invalid-feedback" id="file-error"></div>
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                        <label><span class="text-danger">*</span> Tanggal Mulai :</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                               class="form-control"
                               value="{{ old('tanggal_mulai') }}">
                        <div class="invalid-feedback" id="tanggal_mulai-error"></div>
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                        <label><span class="text-danger">*</span> Tanggal Selesai :</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                               class="form-control"
                               value="{{ old('tanggal_selesai') }}">
                        <div class="invalid-feedback" id="tanggal_selesai-error"></div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
