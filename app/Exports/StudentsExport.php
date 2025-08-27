<?php

namespace App\Exports;

use App\Models\Student;
use App\Services\AcademicTermService;
use App\Services\DashboardDataService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class StudentsExport implements FromCollection, WithHeadings, WithCustomStartCell, WithEvents
{
    /**
     * Exported data
     */
    public function collection()
    {
        return Student::select(
            'lrn',
            'first_name',
            'last_name',
            'grade_level',
            'program',
            'contact_number',
            'email_address'
        )->get();
    }

    /**
     * Column headings (start on row 6, same as import)
     */
    public function headings(): array
    {
        return [
            'LRN',
            'First Name',
            'Last Name',
            'Grade Level',
            'Program',
            'Contact Number',
            'Email Address'
        ];
    }

    /**
     * Make sure headings start on row 6
     */
    public function startCell(): string
    {
        return 'A6';
    }

    /**
     * Add custom values for row 3 & 4 (Acad Year & Semester)
     */
    public function registerEvents(): array
    {
        $academicYear = app(AcademicTermService::class)->fetchCurrentAcademicTerm();

        return [
            AfterSheet::class => function (AfterSheet $event) use ($academicYear) {
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:G1');

                $sheet->setCellValue('A1', 'Officially Enrolled Students');

                // Apply wrap text + vertical alignment
                $sheet->getStyle('A1')->applyFromArray([
                    'alignment' => [
                        'wrapText'   => true,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                        'size'   => 16,
                    ]
                ]);

                $sheet->mergeCells('A2:G2');

                $sheet->setCellValue('A2', 'This document lists the officially enrolled students for the specified academic year and semester, including their personal and academic details.');

                // Apply wrap text + vertical alignment
                $sheet->getStyle('A2')->applyFromArray([
                    'alignment' => [
                        'wrapText'   => true,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'font' => [
                        'italic' => true,
                        'size'   => 12,
                    ]
                ]);

                // Fix row height
                $sheet->getRowDimension(1)->setRowHeight(40);
                $sheet->getRowDimension(2)->setRowHeight(60);

                // ✅ Prevent column A from auto-sizing (so it doesn’t stretch)
                $sheet->getColumnDimension('A')->setAutoSize(false);

                $sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                ]);

                $sheet->getStyle('A2:F2')->applyFromArray([
                    'font' => [
                        'size' => 12,
                    ],
                ]);
                $sheet->getStyle('A3:C3')->applyFromArray([
                    'font' => [
                        'size' => 12,
                    ],
                ]);
                $sheet->getStyle('A4:C4')->applyFromArray([
                    'font' => [
                        'size' => 12,
                    ],
                ]);

                // Row 3 - Academic Year
                $sheet->setCellValue('A3', 'Academic Year:');
                // TODO: Replace with your actual Acad Year fetch
                $sheet->setCellValue('B3', $academicYear->year);

                // Row 4 - Semester
                $sheet->setCellValue('A4', 'Semester:');
                // TODO: Replace with your actual Semester fetch
                $sheet->setCellValue('B4', $academicYear->semester);

                $sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                ]);

                $sheet->getStyle('A6:G6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'], // white text
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1A3165'], // blue background
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'], // black border
                        ],
                    ],
                ]);
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A7:A{$lastRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                    ],
                ]);

                foreach (range('A', 'G') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Row 5 intentionally left blank
            }
        ];
    }
}
