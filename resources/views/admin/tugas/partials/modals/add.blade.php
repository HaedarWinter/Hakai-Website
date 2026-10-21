<!-- Add Task Modal -->
<div class="modal fade" id="modalAddTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus mr-2"></i> Tambah Tugas Baru
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-add-task" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-user text-gray-600 mr-1"></i> Karyawan
                        </label>
                        <select name="user_id" class="form-control" required>
                            <option value="" selected disabled>-- Pilih Karyawan --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-tasks text-gray-600 mr-1"></i> Deskripsi Tugas
                        </label>
                        <textarea name="tugas" class="form-control" rows="3" placeholder="Deskripsi tugas..."
                            required></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-file text-gray-600 mr-1"></i> File Pendukung (opsional)
                        </label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="file">
                            <label class="custom-file-label" for="customFile">Pilih file</label>
                        </div>
                        <small class="form-text text-muted">Semua format file didukung (max 50MB)</small>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label font-weight-bold">
                                <i class="fas fa-calendar-alt text-gray-600 mr-1"></i> Tanggal Mulai
                            </label>
                            <input type="datetime-local" name="tanggal_mulai" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label font-weight-bold">
                                <i class="fas fa-calendar-check text-gray-600 mr-1"></i> Tanggal Selesai
                            </label>
                            <input type="datetime-local" name="tanggal_selesai" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-flag text-gray-600 mr-1"></i> Prioritas
                        </label>
                        <select name="priority" class="form-control">
                            <option value="low">Rendah</option>
                            <option value="medium" selected>Sedang</option>
                            <option value="high">Tinggi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Tugas</button>
                </div>
            </form>
        </div>
    </div>
</div>