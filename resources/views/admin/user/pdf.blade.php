<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Data User</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #999;
            padding: 6px;
            text-align: center;
        }
        thead th {
            background: #f5f5f5;
            font-weight: bold;
        }
        tfoot td {
            font-weight: bold;
            background: #eee;
        }
        .label-admin {
            background-color: #B3E5FC;
            color: #0277BD;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .label-user {
            background-color: #C8E6C9;
            color: #2E7D32;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 4px;
        }
        .done {
            background-color: #C8E6C9;
            color: #2E7D32;
            font-weight: bold;
        }
        .pending {
            background-color: #FFCDD2;
            color: #C62828;
            font-weight: bold;
        }
        .confidential {
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>

<h3 align="center">LAPORAN DATA PENGGUNA SISTEM - Hakai</h3>
<p align="center">
    Tanggal Cetak: {{ $tanggal }} | Pukul: {{ $jam }}
</p>

{{-- Ringkasan Data --}}
<table>
    <thead>
    <tr>
        <th>Admin</th>
        <th>{{ $stats['admin_count'] }} orang</th>
        <th>User/Karyawan</th>
        <th>{{ $stats['user_count'] }} orang</th>
        <th>Total Keseluruhan</th>
        <th>{{ $stats['total'] }} orang</th>
    </tr>
    </thead>
</table>

{{-- Status Tugas --}}
<table>
    <thead>
    <tr>
        <th colspan="6">TABEL CEKLIS STATUS TUGAS</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="2">Tugas Selesai (User)</td>
        <td>{{ $stats['tugas_selesai'] }} ({{ $stats['persentase_selesai'] }}%)</td>
        <td colspan="2">Tugas Belum (User)</td>
        <td>{{ $stats['tugas_belum_selesai'] }} ({{ 100 - $stats['persentase_selesai'] }}%)</td>
    </tr>
    </tbody>
</table>

{{-- Data User --}}
<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Nama Lengkap</th>
        <th>Email</th>
        <th>Jabatan</th>
        <th>Status</th>
        <th>Keterangan</th>
    </tr>
    </thead>
    <tbody>
    @forelse($user as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td align="left">{{ $item->nama ?? 'N/A' }}</td>
            <td align="left">{{ $item->email ?? 'N/A' }}</td>
            <td>
                @if(strtolower($item->jabatan ?? '') === 'admin')
                    <span class="label-admin">{{ $item->jabatan }}</span>
                @else
                    <span class="label-user">{{ $item->jabatan ?? 'User' }}</span>
                @endif
            </td>
            <td>
                @if(strtolower($item->jabatan ?? '') === 'admin')
                    <span class="label-admin">Menugaskan</span>
                @elseif($item->last_seen && $item->last_seen->gt(now()->subMinutes(5)))
                    <span class="done">Online</span>
                @else
                    <span class="pending">Offline</span>
                @endif
            </td>
            <td>
                {{ $item->last_seen ? $item->last_seen->format('d/m/Y H:i') : '-' }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" align="center">Tidak ada data pengguna</td>
        </tr>
    @endforelse
    </tbody>
    <tfoot>
    <tr>
        <td colspan="6" class="confidential">
            <strong>CONFIDENTIAL</strong> - Data ini hanya untuk internal
        </td>
    </tr>
    </tfoot>
</table>

</body>
</html>
