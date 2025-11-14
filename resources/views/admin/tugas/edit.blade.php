@extends('layouts.app')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-edit"></i>
        {{ $title }}
    </h1>

    <div class="card">
        <!-- Card Header -->
        <div class="card-header bg-warning d-flex flex-wrap py-3">
            <div class="mb-1 mr-2">
                <a href="{{ route('tugas.index') }}" class="btn btn-sm border-5 btn-outline-light">
                    <i class="fas fa-arrow-circle-left mr-1"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <form action="{{ route('tugas.update', $tugas->id) }}" method="POST">
                @csrf
                <div class="row">
                    <!-- Insert Column Nama -->
                    <div class="col-12 mb-3">
                        <label for="user_id">
                            <span class="text-danger">*</span>
                            Nama :
                        </label>
                        <input type="text" value="{{$tugas->user->nama}}"
                               class="form-control" disabled>
                    </div>
                    <!-- Nama -->
                    <div class="col-md-12 col-12 mb-3">
                        <label><span class="text-danger">*</span>
                            Tugas :
                        </label>
                        <textarea name="tugas" rows="5" class="form-control
                        @error('tugas') is-invalid @enderror">{{$tugas->tugas}}</textarea>
                        @error('tugas')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                        <label><span class="text-danger">*</span>
                            Tanggal Mulai :
                        </label>
                        <input type="date" name="tanggal_mulai"
                               class="form-control @error('tanggal_mulai') is-invalid
                               @enderror" value="{{$tugas->tanggal_mulai}}">
                        @error('tanggal_mulai')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 col-12 mb-3">
                        <label><span class="text-danger">*</span>
                            Tanggal Selesai :
                        </label>
                        <input type="date" name="tanggal_selesai"
                               class="form-control @error('tanggal_selesai') is-invalid
                               @enderror" value="{{$tugas->tanggal_selesai}}">
                        @error('tanggal_selesai')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
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
