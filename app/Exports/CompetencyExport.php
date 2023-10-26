<?php

namespace App\Exports;

use App\Models\Competency;
use App\Models\TeacherSubject;
use Maatwebsite\Excel\Concerns\FromCollection;

class CompetencyExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // $data = TeacherSubject::mySubject()->pluck('id');
        // return Competency::whereIn('teacher_subject_id', $data);
        return Competency::all();
    }
}
