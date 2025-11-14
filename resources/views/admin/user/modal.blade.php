<!-- Modal -->
<div class="modal fade" id="exampleModal{{$item -> id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="exampleModalLabel"> Hapus {{ $title}} ?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="text-white">&times;</span>
                </button>
            </div>
            <div class="modal-body text-left">
                <div class="row">
                    <div class="col-6">
                        Nama
                    </div>
                    <div class="col-6">
                        : {{$item -> nama}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Email
                    </div>
                    <div class="col-6">
                        :
                        <span class="badge badge-primary">
                            {{$item -> email}}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        Jabatan
                    </div>
                    <div class="col-6">
                        :
                        @if($item -> jabatan == 'Admin')
                            <span class="badge badge-success">
                                {{$item -> jabatan}}
                            </span>
                        @else
                            <span class="badge badge-warning">
                                {{$item -> jabatan}}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        Status
                    </div>
                    <div class="col-6">
                        :
                        @if($item -> is_tugas == false)
                            <span class="badge badge-danger">
                                Belum Ditugaskan
                            </span>
                        @else
                            <span class="badge badge-success">
                                Sudah Ditugaskan
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Tambahkan alert peringatan jika user masih memiliki tugas -->
                @if($item->is_tugas)
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Peringatan!</strong> Posisi karyawan sedang ditugaskan. Data ini tidak dapat dihapus.
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">
                    <i class="fas fa-times"></i>
                    Tutup
                </button>
                <!-- Hanya tampilkan tombol hapus jika user tidak memiliki tugas -->
                @if(!$item->is_tugas)
                    <form action="{{ route('user.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
