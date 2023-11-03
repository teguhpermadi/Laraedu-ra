<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherGrade;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportExcel extends Controller
{
    public function studentGrade()
    {
        $spreadsheet = new Spreadsheet();

        $rowStudentGrade = 2;
        $rowStudent = 2;
        $rowGrade = 2;
        
        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('StudentGrade');
        $sheet1->setCellValue('A1', 'grade_id');
        $sheet1->setCellValue('B1', 'student_id');
        
        // input data student grade
        $studentGrades = StudentGrade::all();
        foreach ($studentGrades as $studentGrade) {
            $sheet1->setCellValue('A' . $rowStudentGrade, $studentGrade->grade_id);
            $sheet1->setCellValue('B' . $rowStudentGrade, $studentGrade->student_id);
            $rowStudentGrade++;
        }

        // Membuat lembar kedua
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->getSheet(1); // Indeks dimulai dari 0
        $sheet2->setTitle('Student');
        $sheet2->setCellValue('A1', 'student_id');
        $sheet2->setCellValue('B1', 'nama_lengkap');
        $sheet2->setCellValue('C1', 'gender');
        $sheet2->setCellValue('d1', 'nis');
        $sheet2->setCellValue('e1', 'nisn');

        // input data students
        $students = Student::all();
        foreach ($students as $student) {
            $sheet2->setCellValue('A'. $rowStudent, $student->id);
            $sheet2->setCellValue('B'. $rowStudent, $student->name);
            $sheet2->setCellValue('C'. $rowStudent, $student->gender);
            $sheet2->setCellValue('D'. $rowStudent, $student->nis);
            $sheet2->setCellValue('E'. $rowStudent, $student->nisn);
            $rowStudent++;
        }

        // Membuat lembar ketiga
        $spreadsheet->createSheet();
        $sheet3 = $spreadsheet->getSheet(2);
        $sheet3->setTitle('Grade');
        $sheet3->setCellValue('A1', 'grade_id');
        $sheet3->setCellValue('B1', 'nama_kelas');
        $sheet3->setCellValue('C1', 'jenjang');
        $sheet3->setCellValue('D1', 'fase');

        // input data grades
        $grades = Grade::all();
        foreach ($grades as $grade) {
            $sheet3->setCellValue('A'.$rowGrade, $grade->id);
            $sheet3->setCellValue('B'.$rowGrade, $grade->name);
            $sheet3->setCellValue('C'.$rowGrade, $grade->grade);
            $sheet3->setCellValue('D'.$rowGrade, $grade->fase);
            $rowGrade++;
        }
        
        $writer = new Xlsx($spreadsheet);
        // $writer->save('student grade.xlsx');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="studentGrade.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }

    public function teacherSubject()
    {
        $spreadsheet = new Spreadsheet();

        $rowGrade = 2;
        
        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('TeacherSubject');
        $sheet1->setCellValue('A1', 'teacher_id');
        $sheet1->setCellValue('B1', 'subject_id');
        $sheet1->setCellValue('C1', 'grade_id');

        // input teacher subject
        $teacherSubjects = TeacherSubject::all();
        $rowTeacherSubject = 2;
        foreach ($teacherSubjects as $teacherSubject) {
            $sheet1->setCellValue('A'.$rowTeacherSubject, $teacherSubject->teacher_id);
            $sheet1->setCellValue('B'.$rowTeacherSubject, $teacherSubject->subject_id);
            $sheet1->setCellValue('C'.$rowTeacherSubject, $teacherSubject->grade_id);  
            $rowTeacherSubject++;
        }

        // membuat lembar kedua
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->getSheet(1); // Indeks dimulai dari 0
        $sheet2->setTitle('Teacher');
        $sheet2->setCellValue('A1', 'teacher_id');
        $sheet2->setCellValue('B1', 'nama');
        $sheet2->setCellValue('C1', 'gender');

        // input teacher
        $teachers = Teacher::all();
        $rowTeacher = 2;
        foreach ($teachers as $teacher) {
            $sheet2->setCellValue('A'.$rowTeacher, $teacher->id);
            $sheet2->setCellValue('B'.$rowTeacher, $teacher->name);
            $sheet2->setCellValue('C'.$rowTeacher, $teacher->gender);
            $rowTeacher++;
        }

        // membuat lembar ketiga
        $spreadsheet->createSheet();
        $sheet3 = $spreadsheet->getSheet(2); // Indeks dimulai dari 0
        $sheet3->setTitle('Subject');
        $sheet3->setCellValue('A1', 'id');
        $sheet3->setCellValue('B1', 'name');
        $sheet3->setCellValue('C1', 'code');

        // input subject
        $subjects = Subject::all();
        $rowSubject = 2; // Mulai dari baris kedua (A2, B2, C2, ...)
        foreach ($subjects as $subject) {
            $sheet3->setCellValue('A'.$rowSubject, $subject->id);
            $sheet3->setCellValue('B'.$rowSubject, $subject->name);
            $sheet3->setCellValue('C'.$rowSubject, $subject->code);
            $rowSubject++;
        }

        // membuat lembar ke empat
        $spreadsheet->createSheet();
        $sheet4 = $spreadsheet->getSheet(3);
        $sheet4->setTitle('Grade');
        $sheet4->setCellValue('A1', 'grade_id');
        $sheet4->setCellValue('B1', 'nama_kelas');
        $sheet4->setCellValue('C1', 'jenjang');
        $sheet4->setCellValue('D1', 'fase');

        // input data grades
        $grades = Grade::all();
        foreach ($grades as $grade) {
            $sheet4->setCellValue('A'.$rowGrade, $grade->id);
            $sheet4->setCellValue('B'.$rowGrade, $grade->name);
            $sheet4->setCellValue('C'.$rowGrade, $grade->grade);
            $sheet4->setCellValue('D'.$rowGrade, $grade->fase);
            $rowGrade++;
        }

        $writer = new Xlsx($spreadsheet);
        // $writer->save('student grade.xlsx');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="teacherSubject.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
    }

    public function teacherGrade()
    {
        // Inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();

        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('TeacherGrade');
        $sheet1->setCellValue('A1', 'teacher_id');
        $sheet1->setCellValue('B1', 'grade_id');

        // Input data TeacherGrade
        $teacherGrades = TeacherGrade::all();
        $rowTeacherGrade = 2;
        foreach ($teacherGrades as $teacherGrade) {
            $sheet1->setCellValue('A'.$rowTeacherGrade, $teacherGrade->teacher_id);
            $sheet1->setCellValue('B'.$rowTeacherGrade, $teacherGrade->grade_id);
            $rowTeacherGrade++;
        }

        // Membuat lembar kedua
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->getSheet(1); // Indeks dimulai dari 0
        $sheet2->setTitle('Teacher');
        $sheet2->setCellValue('A1', 'id');
        $sheet2->setCellValue('B1', 'name');
        $sheet2->setCellValue('C1', 'gender');

        // Input data Teacher
        $teachers = Teacher::all();
        $rowTeacher = 2;
        foreach ($teachers as $teacher) {
            $sheet2->setCellValue('A'.$rowTeacher, $teacher->id);
            $sheet2->setCellValue('B'.$rowTeacher, $teacher->name);
            $sheet2->setCellValue('C'.$rowTeacher, $teacher->gender);
            $rowTeacher++;
        }

        // Membuat lembar ketiga
        $spreadsheet->createSheet();
        $sheet3 = $spreadsheet->getSheet(2);
        $sheet3->setTitle('Grade');
        $sheet3->setCellValue('A1', 'id');
        $sheet3->setCellValue('B1', 'name');
        $sheet3->setCellValue('C1', 'grade');
        $sheet3->setCellValue('D1', 'fase');

        // Input data Grade
        $grades = Grade::all();
        $rowGrade = 2;
        foreach ($grades as $grade) {
            $sheet3->setCellValue('A'.$rowGrade, $grade->id);
            $sheet3->setCellValue('B'.$rowGrade, $grade->name);
            $sheet3->setCellValue('C'.$rowGrade, $grade->grade);
            $sheet3->setCellValue('D'.$rowGrade, $grade->fase);
            $rowGrade++;
        }

        // Membuat file Excel
        $writer = new Xlsx($spreadsheet);

        // Set header untuk mengunduh file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="teacherGrade.xls"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');

    }
}
