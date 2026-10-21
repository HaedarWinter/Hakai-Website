<!-- Filter Section -->
<div class="collapse mb-4" id="filterCollapse">
    <div class="card card-body bg-gray-100 border-0 shadow-sm rounded-lg">
        <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-filter mr-1"></i> Filter Data</h6>
        <div class="row">
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-gray-700">Filter Status:</label>
                <select id="filter-status" class="form-control form-control-sm">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="submitted">Menunggu Persetujuan</option>
                    <option value="approved">Disetujui</option>
                    <option value="rejected">Ditolak</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-gray-700">Filter Karyawan:</label>
                <select id="filter-user" class="form-control form-control-sm">
                    <option value="">Semua Karyawan</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-gray-700">Filter Prioritas:</label>
                <select id="filter-priority" class="form-control form-control-sm">
                    <option value="">Semua Prioritas</option>
                    <option value="high">Tinggi</option>
                    <option value="medium">Sedang</option>
                    <option value="low">Rendah</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label class="small font-weight-bold text-gray-700">&nbsp;</label>
                <button id="reset-filter" class="btn btn-secondary btn-sm btn-block">
                    <i class="fas fa-undo mr-1"></i> Reset Filter
                </button>
            </div>
        </div>
    </div>
</div>