<?php

namespace App\Exports;

use App\Models\User;
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

class UserExport implements FromView, ShouldAutoSize, WithEvents, WithProperties, WithTitle
{
    private $users;
    private $stats;
    private $statsService;

    public function __construct()
    {
        $this->statsService = app(StatisticsService::class);
        $this->users = User::orderBy('jabatan', 'ASC')->get();
        $this->stats = $this->statsService->calculateUserStats($this->users);
    }

    public function view(): View
    {
        $data = [
            'user' => $this->users,
            'stats' => $this->stats,
            'tanggal' => now()->format('d F Y'),
            'jam' => now()->format('H:i:s'),
            'bulan_tahun' => now()->format('F Y'),
        ];
        return view('admin/user/excel', $data);
    }

    public function title(): string
    {
        return 'Data User - ' . now()->format('M Y');
    }

    public function properties(): array
    {
        return [
            'creator' => 'System Administrator',
            'lastModifiedBy' => 'User Management System',
            'title' => 'Data User Report',
            'description' => 'Laporan data pengguna dengan status penugasan',
            'subject' => 'User Data Export',
            'keywords' => 'users,data,export,report',
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
                $sheet->setAutoFilter("A10:F{$lastRow}");

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
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(15);

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
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN DATA PENGGUNA SISTEM - Hakai');

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
        $sheet->mergeCells('A2:C2');
        $sheet->mergeCells('D2:F2');

        $sheet->getStyle('A2:F2')->applyFromArray([
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
        $sheet->mergeCells('A4:F4');
        $sheet->setCellValue('A4', 'DASHBOARD RINGKASAN DATA');

        // Ringkasan Total User (Row 5)
        $sheet->mergeCells('A5:F5');
        $sheet->setCellValue('A5', 'RINGKASAN TOTAL USER');

        // Detail Total User (Row 6)
        $sheet->setCellValue('A6', 'Admin');
        $sheet->setCellValue('B6', $this->stats['admin_count'] . ' orang');
        $sheet->setCellValue('C6', 'User/Karyawan');
        $sheet->setCellValue('D6', $this->stats['user_count'] . ' orang');
        $sheet->setCellValue('E6', 'Total Keseluruhan');
        $sheet->setCellValue('F6', $this->stats['total'] . ' orang');

        // Header Tabel Ceklis Tugas (Row 7)
        $sheet->mergeCells('A7:F7');
        $sheet->setCellValue('A7', 'TABEL CEKLIS STATUS TUGAS');

        // Detail Status Tugas (Row 8)
        $sheet->mergeCells('A8:B8');
        $sheet->setCellValue('A8', ' Tugas Selesai');
        $sheet->setCellValue('C8', $this->stats['tugas_selesai'] . ' orang (' . $this->stats['persentase_selesai'] . '%)');
        $sheet->mergeCells('D8:E8');
        $sheet->setCellValue('D8', ' Tugas Belum Selesai');
        $sheet->setCellValue('F8', $this->stats['tugas_belum_selesai'] . ' orang (' . (100 - $this->stats['persentase_selesai']) . '%)');

        // Detail per Role (Row 9) - Hanya User, bukan Admin
        $sheet->setCellValue('A9', 'User/Karyawan Selesai');
        $sheet->setCellValue('B9', $this->stats['user_ditugaskan'] . ' (' . $this->stats['user_persentase_selesai'] . '%)');
        $sheet->setCellValue('C9', 'User/Karyawan Belum');
        $sheet->setCellValue('D9', $this->stats['user_belum_ditugaskan'] . ' orang');
        $sheet->mergeCells('E9:F9');
        $sheet->setCellValue('E9', ' *Admin tidak termasuk penugasan');

        // Style header utama (Row 4)
        $sheet->getStyle('A4:F4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '6A1B9A']], // Purple
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '4A148C']]],
        ]);

        // Style section Total User (Row 5)
        $sheet->getStyle('A5:F5')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1565C0']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E3F2FD']], // Light Blue
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '2196F3']]],
        ]);

        // Style detail Total User (Row 6)
        $sheet->getStyle('A6:F6')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '90CAF9']]],
        ]);

        // Warna khusus untuk Admin (kolom A dan B)
        $sheet->getStyle('A6:B6')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'B3E5FC']], // Biru muda
            'font' => ['color' => ['rgb' => '0277BD']], // Biru gelap
        ]);

        // Warna khusus untuk User/Karyawan (kolom C dan D)
        $sheet->getStyle('C6:D6')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'C8E6C9']], // Hijau muda
            'font' => ['color' => ['rgb' => '2E7D32']], // Hijau gelap
        ]);

        // Warna khusus untuk Total (kolom E dan F)
        $sheet->getStyle('E6:F6')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFF9C4']], // Kuning muda
            'font' => ['color' => ['rgb' => 'F57F17']], // Kuning gelap
        ]);

        // Style section Ceklis Tugas (Row 7)
        $sheet->getStyle('A7:F7')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '2E7D32']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E8F5E8']], // Light Green
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4CAF50']]],
        ]);

        // Style detail Ceklis Tugas (Row 8)
        $sheet->getStyle('A8:F8')->applyFromArray([
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '81C784']]],
        ]);

        // Warna untuk Tugas Selesai (kolom A, B, C)
        $sheet->getStyle('A8:C8')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'C8E6C9']], // Hijau muda
            'font' => ['color' => ['rgb' => '1B5E20']], // Hijau gelap
        ]);

        // Warna untuk Tugas Belum Selesai (kolom D, E, F)
        $sheet->getStyle('D8:F8')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFCDD2']], // Merah muda
            'font' => ['color' => ['rgb' => 'B71C1C']], // Merah gelap
        ]);

        // Style detail per Role (Row 9)
        $sheet->getStyle('A9:F9')->applyFromArray([
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
        $headerRange = 'A10:F10'; // Update row number karena ada tambahan statistik
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
        // Style untuk data rows (mulai dari row 11 karena ada tambahan statistik)
        $dataRange = "A11:F{$lastRow}";

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

        // Conditional formatting untuk status dan jabatan
        for ($row = 11; $row <= $lastRow; $row++) {
            $statusCell = $sheet->getCell("E{$row}");
            $jabatanCell = $sheet->getCell("D{$row}");
            $statusValue = $statusCell->getValue();
            $jabatanValue = $jabatanCell->getValue();

            // Zebra striping
            if (($row - 11) % 2 == 0) {
                $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FAFAFA'],
                    ],
                ]);
            }
            $jabatanValue = $sheet->getCell("D{$row}")->getValue();
            // Conditional coloring untuk Jabatan
            if (strtolower($jabatanValue) === 'admin') {
                // Biru muda untuk Admin
                $sheet->getStyle("D{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'B3E5FC'], // Biru muda
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '0277BD'], // Biru gelap
                    ],
                ]);
            } else {
                // Hijau muda untuk User/Karyawan
                $sheet->getStyle("D{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'C8E6C9'], // Hijau muda
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '2E7D32'], // Hijau gelap
                    ],
                ]);
            }

            // Conditional coloring untuk Status Tugas
            if (strpos($statusValue, 'Sudah') !== false) {
                // Hijau untuk yang sudah ditugaskan
                $sheet->getStyle("E{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'C8E6C9'], // Light green
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => '2E7D32'], // Dark green
                    ],
                ]);

                // Highlight seluruh row dengan border hijau
                $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '4CAF50'],
                        ],
                    ],
                ]);

            } else {
                // Merah untuk yang belum ditugaskan
                $sheet->getStyle("E{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => 'FFCDD2'], // Light red
                    ],
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'C62828'], // Dark red
                    ],
                ]);

                // Highlight seluruh row dengan border merah
                $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                    'borders' => [
                        'left' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => 'F44336'],
                        ],
                    ],
                ]);
            }
        }
    }

    private function addBorders($sheet, $lastRow)
    {
        // Border untuk seluruh tabel (mulai dari row 10 untuk header)
        $tableRange = "A10:F{$lastRow}";
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
            ->setOddHeader('&L&B&16Data User Report&R&B&12' . now()->format('d/m/Y H:i'))
            ->setOddFooter('&L&IGenerated by System&C&B&12Confidential&R&BHal. &P dari &N');

        // Print area
        $sheet->getPageSetup()->setPrintArea("A1:F{$lastRow}");

        // Print titles (repeat header on each page - update ke row 10)
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(10, 10);
    }
}
