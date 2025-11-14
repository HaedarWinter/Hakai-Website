@extends('layouts.app')
@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-edit"></i>
        {{ $title }}
    </h1>

    <div class="card">
        <div class="card-header bg-primary d-flex flex-wrap py-3">
            <div class="mb-1 mr-2">
                <a href="{{ route('tugas.index') }}" class="btn btn-sm border-5 btn-outline-light">
                    <i class="fas fa-arrow-circle-left mr-1"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('tugas.update', $tugas->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Nama Tugas -->
                    <div class="col-md-12 col-12 mb-3">
                        <label><span class="text-danger">*</span> Tugas :</label>
                        <textarea name="tugas" id="tugas" rows="1"
                                  class="form-control"
                                  oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ old('tugas', $tugas->tugas) }}</textarea>
                        <div class="invalid-feedback" id="tugas-error"></div>
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                        <label><span class="text-danger">*</span> Tanggal Mulai :</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                               class="form-control"
                               value="{{ old('tanggal_mulai', $tugas->tanggal_mulai) }}">
                        <div class="invalid-feedback" id="tanggal_mulai-error"></div>
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                        <label><span class="text-danger">*</span> Tanggal Selesai :</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                               class="form-control"
                               value="{{ old('tanggal_selesai', $tugas->tanggal_selesai) }}">
                        <div class="invalid-feedback" id="tanggal_selesai-error"></div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
