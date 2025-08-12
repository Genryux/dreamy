<?php

namespace App\Exports;

use App\Models\Students;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Students::select('lrn', 'first_name', 'last_name', 'grade_level', 'age', 'contact_number', 'email_address')->get();
    }

    public function headings(): array
    {
        return [
            'LRN',
            'First Name',
            'Last Name',
            'Grade Level',
            'Age',
            'Contact Number',
            'Email Address'
        ];
    }
}
