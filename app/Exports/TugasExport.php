<?php

namespace App\Exports;

use App\Models\Tugas;
use App\Services\StatisticsService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class TugasExport implements FromView, ShouldAutoSize, WithEvents, WithProperties, WithTitle
{
    private $tugas;
    private $stats;
    private $statsService;

    public function __construct()
    {
        $this->statsService = app(StatisticsService::class);
        $this->tugas = Tugas::with('user')->whereNull('deleted_at')->orderBy('created_at', 'DESC')->get();
        $this->stats = $this->statsService->calculateTaskStats($this->tugas);
        
        // Add additional stats for export
        $totalUserBertugas = $this->tugas->pluck('user.id')->unique()->count();
        $this->stats['total_user_bertugas'] = $totalUserBertugas;
        
        // Legacy stats mapping for backward compatibility with views
        $today = now();
        $tugasSelesai = $this->tugas->filter(fn($item) => 
            $item->tanggal_selesai && $today->gte($item->tanggal_selesai)
        )->count();
        
        $tugasSedangBerjalan = $this->tugas->filter(fn($item) => 
            $item->tanggal_mulai && $today->gte($item->tanggal_mulai) &&
            (!$item->tanggal_selesai || $today->lt($item->tanggal_selesai))
        )->count();
        
        $tugasBelumMulai = $this->tugas->filter(fn($item) => 
            $item->tanggal_mulai && $today->lt($item->tanggal_mulai)
        )->count();
        
        $tugasOverdue = $this->tugas->filter(fn($item) => 
            $item->tanggal_selesai && $today->gt($item->tanggal_selesai)
        )->count();
        
        $totalTugas = $this->tugas->count();
        
        $this->stats = array_merge($this->stats, [
            'tugas_selesai' => $tugasSelesai,
            'tugas_sedang_berjalan' => $tugasSedangBerjalan,
            'tugas_belum_mulai' => $tugasBelumMulai,
            'tugas_overdue' => $tugasOverdue,
            'persentase_selesai' => $totalTugas > 0 ? round(($tugasSelesai / $totalTugas) * 100, 1) : 0,
            'persentase_sedang_berjalan' => $totalTugas > 0 ? round(($tugasSedangBerjalan / $totalTugas) * 100, 1) : 0,
            'persentase_belum_mulai' => $totalTugas > 0 ? round(($tugasBelumMulai / $totalTugas) * 100, 1) : 0,
            'persentase_overdue' => $totalTugas > 0
                ? round(($tugasOverdue / $totalTugas) * 100, 1)
                : 0
        ]);
    }

    public function view(): View
    {
        $data = [
            'tugas' => $this->tugas,
            'stats' => $this->stats,
            'tanggal' => now()->format('d F Y'),
            'jam' => now()->format('H:i:s'),
            'bulan_tahun' => now()->format('F Y'),
        ];
        return view('admin/tugas/excel', $data);
    }

    public function title(): string
    {
        return 'Data Tugas - ' . now()->format('M Y');
    }

    public function properties(): array
    {
        return [
            'creator' => 'System Administrator',
            'lastModifiedBy' => 'Task Management System',
            'title' => 'Data Tugas Report',
            'description' => 'Laporan data tugas dengan status penugasan',
            'subject' => 'Task Data Export',
            'keywords' => 'tasks,data,export,report',
            'category' => 'Reports',
            'manager' => 'Admin',
            'company' => 'Your Company Name',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Setup page
                $this->setupPage($sheet);

                // Style header utama
                $this->styleMainHeader($sheet);

                // Style info tanggal dan jam
                $this->styleDateTimeInfo($sheet);

                // Style statistik
                $this->styleStatistics($sheet);

                // Style header tabel
                $this->styleTableHeader($sheet);

                // Style data rows dengan conditional formatting
                $this->styleDataRows($sheet, $lastRow);

                // Add borders
                $this->addBorders($sheet, $lastRow);

                // Freeze panes (update ke row 11)
                $sheet->freezePane('A11');

                // Auto filter (update ke row 10)
                $sheet->setAutoFilter("A10:G{$lastRow}");

                // Setup print area dan footer
                $this->setupPrintSettings($sheet, $lastRow);
            },
        ];
    }

    private function setupPage($sheet)
    {
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);

        // Set row heights
        $sheet->getRowDimension('1')->setRowHeight(25);
        $sheet->getRowDimension('2')->setRowHeight(20);
        $sheet->getRowDimension('3')->setRowHeight(20);
        $sheet->getRowDimension('4')->setRowHeight(30);
        $sheet->getRowDimension('7')->setRowHeight(25);
    }

    private function styleMainHeader($sheet)
    {
        // Merge cells untuk judul utama
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'LAPORAN DATA TUGAS SISTEM - Hakai');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['rgb' => '2F4F4F'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'E6F3FF'],
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '4A90E2'],
                ],
            ],
        ]);
    }

    private function styleDateTimeInfo($sheet)
    {
        // Tanggal dan jam
        $sheet->mergeCells('A2:D2');
        $sheet->mergeCells('E2:G2');

        $sheet->getStyle('A2:G2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => '4A4A4A'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => 'F8F9FA'],
            ],
        ]);
    }

    private function styleStatistics($sheet)
    {
        // Baris kosong
        $sheet->getRowDimension('3')->setRowHeight(10);

        // Header statistik utama
        $sheet->mergeCells('A4:G4');
        $sheet->setCellValue('A4', 'DASHBOARD RINGKASAN DATA TUGAS');

        // Ringkasan Total Tugas (Row 5)
        $sheet->mergeCells('A5:G5');
        $sheet->setCellValue('A5', 'RINGKASAN TOTAL TUGAS');

        // Detail Total Tugas (Row 6)
        $sheet->setCellValue('A6', 'Total Tugas');
        $sheet->setCellValue('B6', $this->stats['total'] . ' tugas');
        $sheet->setCellValue('C6', 'Total User Bertugas');
        $sheet->setCellValue('D6', $this->stats['total_user_bertugas'] . ' orang');
        $sheet->mergeCells('E6:F6');
        $sheet->setCellValue('E6', 'Rata-rata per User');
        $sheet->setCellValue('G6', $this->stats['total_user_bertugas'] > 0 ?
            round($this->stats['total'] / $this->stats['total_user_bertugas'], 1) . ' tugas' : '0 tugas');

        // Header Status Tugas (Row 7)
        $sheet->mergeCells('A7:G7');
        $sheet->setCellValue('A7', 'STATUS PROGRESS TUGAS');

        // Detail Status Tugas (Row 8)
        $sheet->setCellValue('A8', 'Selesai');
        $sheet->setCellValue('B8', $this->stats['tugas_selesai'] . ' (' . $this->stats['persentase_selesai'] . '%)');
        $sheet->setCellValue('C8', 'Sedang Berjalan');
        $sheet->setCellValue('D8', $this->stats['tugas_sedang_berjalan'] . ' (' . $this->stats['persentase_sedang_berjalan'] . '%)');
        $sheet->setCellValue('E8', 'Belum Mulai');
        $sheet->setCellValue('F8', $this->stats['tugas_belum_mulai'] . ' (' . $this->stats['persentase_belum_mulai'] . '%)');
        $sheet->setCellValue('G8', 'Overdue: ' . $this->stats['tugas_overdue']);

        // Detail Performance (Row 9)
        $sheet->mergeCells('A9:B9');
        $sheet->setCellValue('A9', 'Performance Rate');
        $performanceRate = $this->stats['persentase_selesai'] >= 80 ? 'Sangat Baik' :
            ($this->stats['persentase_selesai'] >= 60 ? 'Baik' :
                ($this->stats['persentase_selesai'] >= 40 ? 'Cukup' : 'Perlu Peningkatan'));
        $sheet->setCellValue('C9', $performanceRate);
        $sheet->mergeCells('D9:E9');
        $sheet->setCellValue('D9', 'Status Overdue');
        $overdueStatus = $this->stats['persentase_overdue'] <= 5 ? 'Sangat Baik' :
            ($this->stats['persentase_overdue'] <= 15 ? 'Baik' : 'Perlu Perhatian');
        $sheet->mergeCells('F9:G9');
        $sheet->setCellValue('F9', $overdueStatus . ' (' . $this->stats['persentase_overdue'] . '%)');

        // Style header utama (Row 4)
        $sheet->getStyle('A4:G4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '6A1B9A']], // Purple
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '4A148C']]],
        ]);

        // Style section Total Tugas (Row 5)
        $sheet->getStyle('A5:G5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1565C0']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E3F2FD']], // Light Blue
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '2196F3']]],
        ]);

        // Style detail Total Tugas (Row 6)
        $sheet->getStyle('A6:G6')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '90CAF9']]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F3E5F5']], // Light purple
        ]);

        // Style section Status Tugas (Row 7)
        $sheet->getStyle('A7:G7')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '2E7D32']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E8F5E8']], // Light Green
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4CAF50']]],
        ]);

        // Style detail Status Tugas (Row 8) dengan warna conditional
        $sheet->getStyle('A8:G8')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '81C784']]],
        ]);

        // Warna untuk Selesai (A8:B8)
        $sheet->getStyle('A8:B8')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'C8E6C9']], // Hijau
            'font' => ['color' => ['rgb' => '1B5E20']],
        ]);

        // Warna untuk Sedang Berjalan (C8:D8)
        $sheet->getStyle('C8:D8')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFF3E0']], // Orange
            'font' => ['color' => ['rgb' => 'E65100']],
        ]);

        // Warna untuk Belum Mulai (E8:F8)
        $sheet->getStyle('E8:F8')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E3F2FD']], // Biru
            'font' => ['color' => ['rgb' => '0D47A1']],
        ]);

        // Warna untuk Overdue (G8)
        $sheet->getStyle('G8')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFCDD2']], // Merah
            'font' => ['color' => ['rgb' => 'B71C1C']],
        ]);

        // Style Performance (Row 9)
        $sheet->getStyle('A9:G9')->applyFromArray([
            'font' => ['size' => 9, 'italic' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F5F5F5']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'BDBDBD']]],
        ]);

        // Set row heights untuk statistik
        $sheet->getRowDimension('4')->setRowHeight(25);
        $sheet->getRowDimension('5')->setRowHeight(20);
        $sheet->getRowDimension('6')->setRowHeight(25);
        $sheet->getRowDimension('7')->setRowHeight(20);
        $sheet->getRowDimension('8')->setRowHeight(25);
        $sheet->getRowDimension('9')->setRowHeight(20);
    }

    private function styleTableHeader($sheet)
    {
        $headerRange = 'A10:G10';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '1976D2'], // Material Blue
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '0D47A1'],
                ],
            ],
        ]);

        // Set tinggi row header
        $sheet->getRowDimension('10')->setRowHeight(25);
    }

    private function styleDataRows($sheet, $lastRow)
    {
        // Style untuk data rows (mulai dari row 11)
        $dataRange = "A11:G{$lastRow}";
        $today = now();

        // Base style untuk semua data
        $sheet->getStyle($dataRange)->applyFromArray([
            'font' => [
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Conditional formatting berdasarkan status tugas
        for ($row = 11; $row <= $lastRow; $row++) {
            // Zebra striping
            if (($row - 11) % 2 == 0) {
                $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FAFAFA'],
                    ],
                ]);
            }

            // Conditional coloring berdasarkan status tugas
            $tanggalMulai = $sheet->getCell("D{$row}")->getValue();
            $tanggalSelesai = $sheet->getCell("E{$row}")->getValue();

            if ($tanggalMulai && $tanggalSelesai) {
                $mulai = \Carbon\Carbon::parse($tanggalMulai);
                $selesai = \Carbon\Carbon::parse($tanggalSelesai);

                if ($today->gte($selesai)) {
                    // Tugas Selesai - Hijau
                    $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                        'borders' => [
                            'left' => [
                                'borderStyle' => Border::BORDER_THICK,
                                'color' => ['rgb' => '4CAF50'],
                            ],
                        ],
                    ]);
                    $sheet->getStyle("F{$row}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'C8E6C9']],
                        'font' => ['bold' => true, 'color' => ['rgb' => '2E7D32']],
                    ]);
                } elseif ($today->gte($mulai) && $today->lt($selesai)) {
                    // Sedang Berjalan - Orange
                    $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                        'borders' => [
                            'left' => [
                                'borderStyle' => Border::BORDER_THICK,
                                'color' => ['rgb' => 'FF9800'],
                            ],
                        ],
                    ]);
                    $sheet->getStyle("F{$row}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFF3E0']],
                        'font' => ['bold' => true, 'color' => ['rgb' => 'E65100']],
                    ]);
                } elseif ($today->lt($mulai)) {
                    // Belum Mulai - Biru
                    $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                        'borders' => [
                            'left' => [
                                'borderStyle' => Border::BORDER_THICK,
                                'color' => ['rgb' => '2196F3'],
                            ],
                        ],
                    ]);
                    $sheet->getStyle("F{$row}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E3F2FD']],
                        'font' => ['bold' => true, 'color' => ['rgb' => '0D47A1']],
                    ]);
                }

                // Check overdue
                if ($today->gt($selesai)) {
                    $sheet->getStyle("G{$row}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFCDD2']],
                        'font' => ['bold' => true, 'color' => ['rgb' => 'C62828']],
                    ]);
                }
            }
        }
    }

    private function addBorders($sheet, $lastRow)
    {
        // Border untuk seluruh tabel (mulai dari row 10 untuk header)
        $tableRange = "A10:G{$lastRow}";
        $sheet->getStyle($tableRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '666666'],
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '333333'],
                ],
            ],
        ]);
    }

    private function setupPrintSettings($sheet, $lastRow)
    {
        // Setup halaman
        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToPage(true)
            ->setFitToWidth(1)
            ->setFitToHeight(0);

        // Margins
        $sheet->getPageMargins()
            ->setTop(0.75)
            ->setRight(0.25)
            ->setLeft(0.25)
            ->setBottom(0.75)
            ->setHeader(0.3)
            ->setFooter(0.3);

        // Header and Footer
        $sheet->getHeaderFooter()
            ->setOddHeader('&L&B&16Data Tugas Report&R&B&12' . now()->format('d/m/Y H:i'))
            ->setOddFooter('&L&IGenerated by System&C&B&12Confidential&R&BHal. &P dari &N');

        // Print area
        $sheet->getPageSetup()->setPrintArea("A1:G{$lastRow}");

        // Print titles (repeat header on each page)
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(10, 10);
    }
}
