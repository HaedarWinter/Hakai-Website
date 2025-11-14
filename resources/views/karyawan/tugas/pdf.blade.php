@extends('layouts.pdf-base')

@section('title', 'Laporan Tugas Saya')

@section('content')
<div class="header">
    <h1>LAPORAN TUGAS SAYA</h1>
    <p>Nama: {{ Auth::user()->nama }} | Email: {{ Auth::user()->email }}</p>
    <p>Periode: {{ $data['tanggal'] }} | Pukul: {{ $data['jam'] }}</p>
</div>

<table>
    <thead>
    <tr>
        <th class="text-center">No</th>
        <th>Tugas</th>
        <th class="text-center">Tanggal Mulai</th>
        <th class="text-center">Tanggal Selesai</th>
        <th class="text-center">Status</th>
        <th class="text-center">Catatan</th>
    </tr>
    </thead>
    <tbody>
    @forelse($data['tugas'] as $index => $tugas)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $tugas->tugas }}</td>
            <td class="text-center">{{ $tugas->tanggal_mulai->format('d-m-Y') }}</td>
            <td class="text-center">{{ $tugas->tanggal_selesai->format('d-m-Y') }}</td>
            <td class="text-center">
                <x-pdf-status-badge :status="$tugas->status" />
            </td>
            <td>{{ $tugas->catatan ?? '-' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center">Tidak ada data tugas</td>
        </tr>
    @endforelse
    </tbody>
</table>
@endsection
