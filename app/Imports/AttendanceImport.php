<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendanceImport implements ToModel, WithHeadingRow, WithUpsertColumns, WithUpserts
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Attendance([
            'academic_year_id' => $row['academic_year_id'],
            'grade_id' => $row['grade_id'],
            'student_id' => $row['student_id'],
            'sick' => $row['sakit'],
            'permission' => $row['izin'],
            'absent' => $row['tanpa_keterangan'],
            'note' => $row['catatan'],
            'achievement' => $row['penghargaan'],
        ]);
    }

    public function headingRow(): int
    {
        return 3;
    }

    /**
     * @return array
     */
    public function upsertColumns()
    {
        return [
            'sick',
            'permission',
            'absent',
            'note',
            'achievement',
        ];
    }

    public function uniqueBy()
    {
        return [
            'academic_year_id',
            'grade_id',
            'student_id',
        ];
    }
}
