<!-- Create User Modal -->
<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus mr-2"></i> Tambah User Baru
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-add-user">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user text-gray-600 mr-1"></i> Nama Lengkap
                        </label>
                        <input type="text" name="nama" class="form-control form-control-lg"
                            placeholder="Masukkan nama lengkap" required>
                        <div class="invalid-feedback" id="nama-error"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope text-gray-600 mr-1"></i> Email
                        </label>
                        <input type="email" name="email" class="form-control form-control-lg"
                            placeholder="contoh@email.com" required>
                        <div class="invalid-feedback" id="email-error"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-briefcase text-gray-600 mr-1"></i> Jabatan
                        </label>
                        <select name="jabatan" class="form-control form-control-lg" required>
                            <option value="" selected disabled>-- Pilih Jabatan --</option>
                            <option value="Admin">Administrator</option>
                            <option value="Karyawan">Karyawan</option>
                        </select>
                        <div class="invalid-feedback" id="jabatan-error"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock text-gray-600 mr-1"></i> Password
                        </label>
                        <input type="password" name="password" class="form-control form-control-lg"
                            placeholder="Minimal 8 karakter" required>
                        <div class="invalid-feedback" id="password-error"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock text-gray-600 mr-1"></i> Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" class="form-control form-control-lg"
                            placeholder="Ulangi password" required>
                        <div class="invalid-feedback" id="password_confirmation-error"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>