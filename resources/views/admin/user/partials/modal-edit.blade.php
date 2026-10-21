<!-- Edit User Modal -->
<div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit mr-2"></i> Edit User
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit-user">
                @csrf
                <input type="hidden" id="edit-id" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user text-gray-600 mr-1"></i> Nama Lengkap
                        </label>
                        <input type="text" id="edit-nama" name="nama" class="form-control form-control-lg"
                            placeholder="Masukkan nama lengkap" required>
                        <div class="invalid-feedback" id="edit-nama-error"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-envelope text-gray-600 mr-1"></i> Email
                        </label>
                        <input type="email" id="edit-email" name="email" class="form-control form-control-lg"
                            placeholder="contoh@email.com" required>
                        <div class="invalid-feedback" id="edit-email-error"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-briefcase text-gray-600 mr-1"></i> Jabatan
                        </label>
                        <select id="edit-jabatan" name="jabatan" class="form-control form-control-lg" required>
                            <option value="Admin">Administrator</option>
                            <option value="Karyawan">Karyawan</option>
                        </select>
                        <div class="invalid-feedback" id="edit-jabatan-error"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock text-gray-600 mr-1"></i> Password (opsional)
                        </label>
                        <input type="password" name="password" class="form-control form-control-lg"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                        <div class="invalid-feedback" id="edit-password-error"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-lock text-gray-600 mr-1"></i> Konfirmasi Password
                        </label>
                        <input type="password" name="password_confirmation" class="form-control form-control-lg"
                            placeholder="Ulangi password baru">
                        <div class="invalid-feedback" id="edit-password_confirmation-error"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync-alt mr-1"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>