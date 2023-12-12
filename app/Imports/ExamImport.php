<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExamImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $data = [];
        foreach ($rows as $row) {
            $data[] = [
                'teacher_subject_id' => $row['teacher_subject_id'],
                'student_id' => $row['student_id'],
                'score_middle' => $row['score_middle'],
                'score_last' => $row['score_last'],
            ];
        }
        return $data;
    }

    public function headingRow(): int
    {
        return 10;
    }
}
