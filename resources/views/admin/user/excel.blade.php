 <table>
            <!-- Header Utama -->
            <thead>
            <!-- Judul Utama (Row 1) -->
            <tr>
                <th colspan="6" align="center">LAPORAN DATA PENGGUNA SISTEM - Hakai</th>
            </tr>

            <!-- Informasi Tanggal dan Jam (Row 2) -->
            <tr>
                <th colspan="3" align="center">Tanggal Cetak: {{ $tanggal }}</th>
                <th colspan="3" align="center">Pukul: {{ $jam }}</th>
            </tr>

            <!-- Baris kosong untuk spacing (Row 3) -->
            <tr>
                <th colspan="6">&nbsp;</th>
            </tr>

            <!-- Header Dashboard (Row 4) -->
            <tr>
                <th colspan="6" align="center"> DASHBOARD RINGKASAN DATA</th>
            </tr>

            <!-- Header Ringkasan Total User (Row 5) -->
            <tr>
                <th colspan="6" align="center">RINGKASAN TOTAL USER</th>
            </tr>

            <!-- Detail Total User (Row 6) -->
            <tr>
                <th align="center">Admin</th>
                <th align="center">{{ $stats['admin_count'] }} orang</th>
                <th align="center">User/Karyawan</th>
                <th align="center">{{ $stats['user_count'] }} orang</th>
                <th align="center">Total Keseluruhan</th>
                <th align="center">{{ $stats['total'] }} orang</th>
            </tr>

            <!-- Header Tabel Ceklis Tugas (Row 7) -->
            <tr>
                <th colspan="6" align="center">TABEL CEKLIS STATUS TUGAS</th>
            </tr>

            <!-- Detail Status Tugas (Row 8) - Hanya User/Karyawan -->
            <tr>
                <th colspan="2" align="center"> Tugas Selesai (User)</th>
                <th align="center">{{ $stats['tugas_selesai'] }} orang ({{ $stats['persentase_selesai'] }}%)</th>
                <th colspan="2" align="center"> Tugas Belum Selesai (User)</th>
                <th align="center">{{ $stats['tugas_belum_selesai'] }} orang ({{ 100 - $stats['persentase_selesai'] }}%)</th>
            </tr>

            <!-- Detail Per Role (Row 9) - Catatan Admin -->
            <tr>
                <th align="center">User Selesai</th>
                <th align="center">{{ $stats['user_ditugaskan'] }} ({{ $stats['user_persentase_selesai'] }}%)</th>
                <th align="center">User Belum</th>
                <th align="center">{{ $stats['user_belum_ditugaskan'] }} orang</th>
                <th colspan="2" align="center"> *Admin tidak termasuk penugasan</th>
            </tr>

            <!-- Header Tabel Data (Row 10) -->
            <tr>
                <th align="center">No</th>
                <th align="center">Nama Lengkap</th>
                <th align="center">Email</th>
                <th align="center">Jabatan</th>
                <th align="center">Status</th>
                <th align="center">Keterangan</th>
            </tr>
            </thead>

            <!-- Body Tabel -->
            <tbody>
            @forelse($user as $item)
                <tr>
                    <!-- Nomor urut -->
                    <td align="center">{{ $loop->iteration }}</td>

                    <!-- Nama lengkap -->
                    <td align="left">{{ $item->nama ?? 'N/A' }}</td>

                    <!-- Email -->
                    <td align="left">{{ $item->email ?? 'N/A' }}</td>

                    <!-- Jabatan dengan conditional styling -->
                    <td align="center">
                        @if(strtolower($item->jabatan ?? '') === 'admin')
                            <span style="background-color: #B3E5FC; color: #0277BD; font-weight: bold; padding: 2px 8px; border-radius: 4px;">
                         {{ $item->jabatan }}
                    </span>
                        @else
                            <span style="background-color: #C8E6C9; color: #2E7D32; font-weight: bold; padding: 2px 8px; border-radius: 4px;">
                         {{ $item->jabatan ?? 'User' }}
                    </span>
                        @endif
                    </td>

                    <!-- Status penugasan dengan conditional formatting -->
                    @if(strtolower($item->jabatan ?? '') === 'admin')
                        <td align="center">
                    <span style="background-color: #B3E5FC; color: #0277BD; font-weight: bold; padding: 2px 8px; border-radius: 4px;">
                        Menugaskan
                    </span>
                        </td>
                        <td align="center">
                    <span style="background-color: #B3E5FC; color: #0277BD; font-weight: bold; padding: 2px 8px; border-radius: 4px;">
                        Menugaskan
                    </span>
                        </td>
                    @elseif($item->last_seen && $item->last_seen->gt(now()->subMinutes(5)))
                        <td align="center" style="background-color: #C8E6C9; color: #2E7D32; font-weight: bold;">
                             Online
                        </td>
                        <td align="center">Terakhir aktif: {{ $item->last_seen->format('d/m/Y H:i') }}</td>
                    @else
                        <td align="center" style="background-color: #FFCDD2; color: #C62828; font-weight: bold;">
                             Offline
                        </td>
                        <td align="center">
                            {{ $item->last_seen ? 'Terakhir aktif: '.$item->last_seen->format('d/m/Y H:i') : 'Belum pernah masuk' }}
                        </td>
                    @endif
                </tr>
            @empty
                <!-- Jika tidak ada data -->
                <tr>
                    <td colspan="6" align="center" style="font-style: italic; color: #666; padding: 20px;">
                         Tidak ada data pengguna yang tersedia
                    </td>
                </tr>
            @endforelse
            </tbody>

            <!-- Footer Summary -->
            <tfoot>
            <!-- Summary Total (berdasarkan Role) -->
            <tr style="background-color: #E3F2FD; font-weight: bold; border-top: 2px solid #1976D2;">
                <td colspan="3" align="center" style="color: #0277BD;">
                     ADMIN: {{ $stats['admin_count'] }} orang (Tidak ada penugasan)
                </td>
                <td colspan="3" align="center" style="color: #2E7D32;">
                     USER: {{ $stats['user_count'] }} orang
                    ( {{ $stats['user_ditugaskan'] }} |  {{ $stats['user_belum_ditugaskan'] }})
                </td>
            </tr>

            <!-- Summary Keseluruhan -->
            <tr style="background-color: #FFF9C4; font-weight: bold;">
                <td colspan="4" align="right" style="color: #F57F17;">TOTAL KESELURUHAN:</td>
                <td align="center" style="color: #2E7D32;">
                     {{ $stats['tugas_selesai'] }} ({{ $stats['persentase_selesai'] }}%)
                </td>
                <td align="center" style="color: #C62828;">
                     {{ $stats['tugas_belum_selesai'] }} ({{ 100 - $stats['persentase_selesai'] }}%)
                </td>
            </tr>

            <!-- Grand Total -->
            <tr style="background-color: #F5F5F5; font-weight: bold; border: 2px solid #666;">
                <td colspan="5" align="right" style="font-size: 12px;"> GRAND TOTAL USERS:</td>
                <td align="center" style="font-size: 14px; color: #1976D2;">
                    <strong>{{ $stats['total'] }} ORANG</strong>
                </td>
            </tr>

            <!-- Timestamp -->
            <tr>
                <td colspan="6" align="center" style="font-size: 10px; color: #666; padding-top: 15px;">
                    <em> Laporan ini dibuat secara otomatis oleh Hakai Website pada {{ $stats['updated_at'] }}</em>
                </td>
            </tr>

            <!-- Performance Insight -->
            <tr>
                <td colspan="6" align="center" style="font-size: 10px; color: #4CAF50; padding-top: 5px;">
                    <strong>
                         INSIGHT: Tingkat penugasan mencapai {{ $stats['persentase_selesai'] }}%
                        @if($stats['persentase_selesai'] >= 80)
                            - Sangat Baik!
                        @elseif($stats['persentase_selesai'] >= 60)
                            - Baik
                        @elseif($stats['persentase_selesai'] >= 40)
                            - Cukup
                        @else
                            - Perlu Peningkatan
                        @endif
                    </strong>
                </td>
            </tr>

            <!-- Confidential -->
            <tr>
                <td colspan="6" align="center" style="font-size: 9px; color: #999; padding-top: 10px; border-top: 1px solid #DDD;">
                    <strong>CONFIDENTIAL</strong> - Data ini bersifat rahasia dan hanya untuk penggunaan internal
                </td>
            </tr>
            </tfoot>
        </table>
