@extends('layouts.pdf-base')

@section('title', 'Laporan Data Tugas')

@section('content')
    <div class="header">
        <h1>LAPORAN DATA TUGAS KARYAWAN</h1>
        <p>Periode: {{ $data['tanggal'] }} | Pukul: {{ $data['jam'] }}</p>
    </div>

    @if(isset($data['stats']))
        <div style="width: 100%; margin-bottom: 30px;">
            <table style="width: 100%; border: none;">
                <tr>
                    <!-- KOLOM KIRI: STATISTIK ANGKA (40%) -->
                    <td style="width: 45%; vertical-align: top; border: none; padding: 0;">
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
                    </td>

                    <!-- KOLOM KANAN: GRAFIK VISUAL (60%) -->
                    <td style="width: 55%; vertical-align: top; border: none; padding-left: 20px;">
                        <div
                            style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 18px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                            <h4
                                style="margin: 0 0 18px 0; text-align: center; color: #2c3e50; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">
                                📊 Statistik Penugasan</h4>

                            <!-- Chart Item: Disetujui -->
                            <div style="margin-bottom: 15px;">
                                <table style="width: 100%; border: none; margin: 0;">
                                    <tr>
                                        <td
                                            style="border: none; padding: 0; width: 70%; font-size: 10px; font-weight: bold; color: #2c3e50;">
                                            ✓ Disetujui</td>
                                        <td
                                            style="border: none; padding: 0; text-align: right; font-size: 10px; font-weight: bold; color: #28a745;">
                                            {{ $data['stats']['approved_percentage'] }}%</td>
                                    </tr>
                                </table>
                                <div
                                    style="background-color: #e9ecef; height: 14px; width: 100%; border-radius: 7px; margin-top: 5px; overflow: hidden;">
                                    <div
                                        style="background: linear-gradient(90deg, #28a745 0%, #20c997 100%); height: 100%; width: {{ $data['stats']['approved_percentage'] }}%; border-radius: 7px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Chart Item: Dikirim -->
                            <div style="margin-bottom: 15px;">
                                <table style="width: 100%; border: none; margin: 0;">
                                    <tr>
                                        <td
                                            style="border: none; padding: 0; width: 70%; font-size: 10px; font-weight: bold; color: #2c3e50;">
                                            📤 Dikirim</td>
                                        <td
                                            style="border: none; padding: 0; text-align: right; font-size: 10px; font-weight: bold; color: #17a2b8;">
                                            {{ $data['stats']['submitted_percentage'] }}%</td>
                                    </tr>
                                </table>
                                <div
                                    style="background-color: #e9ecef; height: 14px; width: 100%; border-radius: 7px; margin-top: 5px; overflow: hidden;">
                                    <div
                                        style="background: linear-gradient(90deg, #17a2b8 0%, #20c9e3 100%); height: 100%; width: {{ $data['stats']['submitted_percentage'] }}%; border-radius: 7px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Chart Item: Menunggu -->
                            <div style="margin-bottom: 15px;">
                                <table style="width: 100%; border: none; margin: 0;">
                                    <tr>
                                        <td
                                            style="border: none; padding: 0; width: 70%; font-size: 10px; font-weight: bold; color: #2c3e50;">
                                            ⏳ Menunggu</td>
                                        <td
                                            style="border: none; padding: 0; text-align: right; font-size: 10px; font-weight: bold; color: #ffc107;">
                                            {{ $data['stats']['pending_percentage'] }}%</td>
                                    </tr>
                                </table>
                                <div
                                    style="background-color: #e9ecef; height: 14px; width: 100%; border-radius: 7px; margin-top: 5px; overflow: hidden;">
                                    <div
                                        style="background: linear-gradient(90deg, #ffc107 0%, #ffcd39 100%); height: 100%; width: {{ $data['stats']['pending_percentage'] }}%; border-radius: 7px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Chart Item: Terlambat -->
                            <div style="margin-bottom: 0;">
                                <table style="width: 100%; border: none; margin: 0;">
                                    <tr>
                                        <td
                                            style="border: none; padding: 0; width: 70%; font-size: 10px; font-weight: bold; color: #2c3e50;">
                                            ⚠️ Terlambat</td>
                                        <td
                                            style="border: none; padding: 0; text-align: right; font-size: 10px; font-weight: bold; color: #dc3545;">
                                            {{ $data['stats']['overdue_percentage'] }}%</td>
                                    </tr>
                                </table>
                                <div
                                    style="background-color: #e9ecef; height: 14px; width: 100%; border-radius: 7px; margin-top: 5px; overflow: hidden;">
                                    <div
                                        style="background: linear-gradient(90deg, #dc3545 0%, #e4606d 100%); height: 100%; width: {{ $data['stats']['overdue_percentage'] }}%; border-radius: 7px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
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