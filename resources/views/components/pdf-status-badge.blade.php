@props(['status'])

@if($status == 'approved')
    <span class="badge badge-success">Disetujui</span>
@elseif($status == 'submitted')
    <span class="badge badge-info">Menunggu Persetujuan</span>
@elseif($status == 'rejected')
    <span class="badge badge-danger">Ditolak</span>
@else
    <span class="badge badge-warning">Pending</span>
@endif

