@extends('layouts.pdf-base')

@section('title', 'Laporan Data Tugas')

@section('content')
<div class="header">
    <h1>LAPORAN DATA TUGAS KARYAWAN</h1>
    <p>Periode: {{ $data['tanggal'] }} | Pukul: {{ $data['jam'] }}</p>
</div>

@if(isset($data['stats']))
    <div class="stats-container">
        <div class="stat-box">
            <h3>{{ $data['stats']['total'] }}</h3>
            <p>Total Tugas</p>
        </div>
        <div class="stat-box">
            <h3>{{ $data['stats']['approved'] }}</h3>
            <p>Disetujui ({{ $data['stats']['approved_percentage'] }}%)</p>
        </div>
        <div class="stat-box">
            <h3>{{ $data['stats']['submitted'] }}</h3>
            <p>Dikirim ({{ $data['stats']['submitted_percentage'] }}%)</p>
        </div>
        <div class="stat-box">
            <h3>{{ $data['stats']['pending'] }}</h3>
            <p>Menunggu ({{ $data['stats']['pending_percentage'] }}%)</p>
        </div>
        <div class="stat-box">
            <h3>{{ $data['stats']['overdue'] }}</h3>
            <p>Terlambat ({{ $data['stats']['overdue_percentage'] }}%)</p>
        </div>
    </div>
@endif

<table>
    <thead>
    <tr>
        <th class="text-center">No</th>
        <th>Karyawan</th>
        <th>Tugas</th>
        <th class="text-center">Tanggal Mulai</th>
        <th class="text-center">Tanggal Selesai</th>
        <th class="text-center">Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse($data['tugas'] as $index => $tugas)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>
                <strong>{{ $tugas->user->nama }}</strong><br>
                <small>{{ $tugas->user->email }}</small>
            </td>
            <td>{{ $tugas->tugas }}</td>
            <td class="text-center">{{ $tugas->tanggal_mulai->format('d-m-Y') }}</td>
            <td class="text-center">{{ $tugas->tanggal_selesai->format('d-m-Y') }}</td>
            <td class="text-center">
                <x-pdf-status-badge :status="$tugas->status" />
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data tugas</td>
        </tr>
    @endforelse
    </tbody>
</table>
@endsection
