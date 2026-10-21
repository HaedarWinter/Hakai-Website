<!-- Edit Task Modal -->
<div class="modal fade" id="modalEditTask" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit mr-2"></i> Edit Tugas
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form-edit-task">
                @csrf
                <input type="hidden" id="edit-id" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-tasks text-gray-600 mr-1"></i> Deskripsi Tugas
                        </label>
                        <textarea id="edit-tugas" name="tugas" class="form-control" rows="3"
                            placeholder="Deskripsi tugas..." required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="form-label font-weight-bold">
                                <i class="fas fa-calendar-alt text-gray-600 mr-1"></i> Tanggal Mulai
                            </label>
                            <input type="datetime-local" id="edit-tanggal_mulai" name="tanggal_mulai"
                                class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label font-weight-bold">
                                <i class="fas fa-calendar-check text-gray-600 mr-1"></i> Tanggal Selesai
                            </label>
                            <input type="datetime-local" id="edit-tanggal_selesai" name="tanggal_selesai"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label font-weight-bold">
                            <i class="fas fa-flag text-gray-600 mr-1"></i> Prioritas
                        </label>
                        <select name="priority" id="edit-priority" class="form-control">
                            <option value="low">Rendah</option>
                            <option value="medium">Sedang</option>
                            <option value="high">Tinggi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update Tugas</button>
                </div>
            </form>
        </div>
    </div>
</div>