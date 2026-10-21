<!-- Super Admin Management Modal -->
<div class="modal fade" id="modalSuperAdmin" tabindex="-1" role="dialog" aria-labelledby="modalSuperAdminLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalSuperAdminLabel">
                    <i class="fas fa-shield-alt mr-2"></i>Manajemen Super Admin
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Perhatian!</strong> Super Admin memiliki akses penuh untuk mengelola semua user termasuk
                    Admin lainnya. Berikan hak ini dengan bijak.
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="superAdminTable">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th>Admin</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Status Super Admin</th>
                                <th class="text-center" style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="super-admin-body">
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>