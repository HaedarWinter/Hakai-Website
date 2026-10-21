<!-- Reject Task Modal -->
<div class="modal fade" id="modalRejectTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times-circle mr-2"></i> Tolak Tugas
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-reject-task">
                @csrf
                <input type="hidden" id="reject-task-id">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Anda akan menolak tugas dari <strong id="reject-task-nama"></strong>: <strong
                            id="reject-task-tugas"></strong>
                    </div>
                    <div class="form-group">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-sticky-note text-gray-600 mr-1"></i> Catatan Penolakan <span
                                class="text-danger">*</span>
                        </label>
                        <textarea name="catatan" id="reject-catatan" class="form-control" rows="4"
                            placeholder="Berikan alasan penolakan..." required></textarea>
                        <small class="text-muted">Catatan ini akan dilihat oleh karyawan</small>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Tugas</button>
                </div>
            </form>
        </div>
    </div>
</div>