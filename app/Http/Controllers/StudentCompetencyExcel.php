<?php

namespace App\Http\Controllers;

use App\Models\Competency;
use App\Models\Student;
use App\Models\StudentCompetency;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentCompetencyExcel extends Controller
{
    public function getData($teacher_subject_id)
    {
        $data = TeacherSubject::with('competencies', 'studentGrade')->find($teacher_subject_id);

        $students = Student::whereIn('id', $data->studentGrade->pluck('student_id'))->get();
        $competencies = $data->competencies;
        // $score = StudentCompetency::whereIn('student_id' )
        // $this->export($data);
        return $competencies;
    }

    public function export($data)
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set aktifkan sheet pertama
        $sheet = $spreadsheet->getActiveSheet();

        // Isi sel dengan data
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIS');
        $sheet->setCellValue('C1', 'Nama Siswa');

        // iterasi kompetensi
        $columnCompetency = 'D';
        foreach ($data['competencies'] as $row) {
            $sheet->setCellValue($columnCompetency.'1', 'teacher_subject_id');
            $columnCompetency++;
            $sheet->setCellValue($columnCompetency.'1', 'student_id');
            $columnCompetency++;
            $sheet->setCellValue($columnCompetency.'1', $row['description']);
            $columnCompetency++;
        }

        // iterasi student
        $rowStudent = 2;
        $no = 1;
        foreach ($data->studentGrade as $student) {
            $sheet->setCellValue('A'.$rowStudent, $no); // nomor
            $sheet->setCellValue('B'.$rowStudent, $student->student->nis); // nis 
            $sheet->setCellValue('C'.$rowStudent, $student->student->name); // nama 
            
            // iterasi skor
            $columnScoreCompetency = 'D';
            foreach ($student->studentCompetency as $competency) {
                $sheet->setCellValue($columnScoreCompetency.$rowStudent, $data['id']); // teacher_subject_id 
                $columnScoreCompetency++;
                $sheet->setCellValue($columnScoreCompetency.$rowStudent, $student->student_id); // student_id 
                $columnScoreCompetency++;
                $sheet->setCellValue($columnScoreCompetency.$rowStudent, $row->score); // student_id 
                $columnScoreCompetency++;
            }

            $rowStudent++;
            $no++;
        }

        // Simpan file Excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('example.xlsx');
    }
}
