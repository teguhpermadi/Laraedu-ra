<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Extracurricular;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentExtracurricular;
use App\Models\StudentGrade;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherExtracurricular;
use App\Models\TeacherGrade;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "studentGrade.xlsx"; // <<< HERE
        $writer->save(base_path($filename));
        return response()->download(base_path('studentGrade.xlsx')); // <<< HERE
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
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "teacherSubject.xlsx"; // <<< HERE
        $writer->save(base_path($filename));
        return response()->download(base_path('teacherSubject.xlsx')); // <<< HERE
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
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "teacherGrade.xlsx"; // <<< HERE
        $writer->save(base_path($filename));
        return response()->download(base_path('teacherGrade.xlsx')); // <<< HERE
    }

    public function studentCompetency($teacher_subject_id)
    {
        $teacherSubject = TeacherSubject::with([
                            'academic',
                            'teacher',
                            'grade',
                            'subject',
                            'competencies.studentCompetency.student', 
                            'exam',
                        ])->find($teacher_subject_id);
        
        $academic = $teacherSubject->academic;
        $teacher = $teacherSubject->teacher;
        $grade = $teacherSubject->grade;
        $subject = $teacherSubject->subject;
        $competencies = $teacherSubject->competencies;

        // Inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();

        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Sheet1');

        // identitas
        $identitas = [
            ['Identitas pelajaran'],
            [null],
            ['Nama Guru', null, null, null, null, ':', $teacher->name],
            ['Mata Pelajaran', null, null, null, null, ':', $subject->name],
            ['Kelas', null, null, null, null, ':', $grade->name],
            ['Tahun Akademik', null, null, null, null, ':', $academic->year],
            ['Semester', null, null, null, null, ':', $academic->semester],
        ];
        $sheet1->fromArray($identitas);

        $rowStart = 10;
        $data = [];
        foreach ($competencies as $competency) {
            $data[] = [
                'deskripsi kompetensi', null, null, null, null, ':', $competency->description
            ];
            $data[] = [null];
            $data[] = [
                'nis','nama siswa','teacher_subject_id','student_id','competency_id','score'
            ];

            foreach ($competency->studentCompetency as $studentCompetency) {
                $data[] = [
                    $studentCompetency->student->nis,
                    $studentCompetency->student->name,
                    $studentCompetency->teacher_subject_id,
                    $studentCompetency->student_id,
                    $studentCompetency->competency_id,
                    $studentCompetency->score,
                ];
            }

            $data[] = [null];
            $data[] = [null];

        }
        
        $sheet1->fromArray($data,null,'A'.$rowStart,true);

        // setting width
        $sheet1->getColumnDimension('B')->setWidth(30);

        // hide coloumn C D E
        $sheet1->getColumnDimension('C')->setVisible(false);
        $sheet1->getColumnDimension('D')->setVisible(false);
        $sheet1->getColumnDimension('E')->setVisible(false);

        // Membuat file Excel
        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "studentCompetency.xlsx"; // <<< HERE
        $writer->save(base_path($filename));
        return response()->download(base_path('studentCompetency.xlsx')); // <<< HERE
    }

    public function studentCompetencySheet($teacher_subject_id)
    {
        $teacherSubject = TeacherSubject::with([
                    'academic',
                    'teacher',
                    'grade',
                    'subject',
                    'competencies.studentCompetency.student', 
                    'exam',
                ])->find($teacher_subject_id);

        $academic = $teacherSubject->academic;
        $teacher = $teacherSubject->teacher;
        $grade = $teacherSubject->grade;
        $subject = $teacherSubject->subject;
        $competencies = $teacherSubject->competencies;

        // Inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();
        $countSheet = 0;
        
        // buat sheet berdasarkan banyaknya kompetensi
        foreach ($competencies as $competency) {
            $spreadsheet->createSheet();
            // Membuat lembar pertama
            $sheet = $spreadsheet->getSheet($countSheet); // Indeks dimulai dari 0
            $sheet->setTitle('Sheet'. ($countSheet+1));

             // identitas
            $identitas = [
                ['Identitas pelajaran'],
                [null],
                ['Nama Guru', null, null, null, null, ':', $teacher->name],
                ['Mata Pelajaran', null, null, null, null, ':', $subject->name],
                ['Kelas', null, null, null, null, ':', $grade->name],
                ['Tahun Akademik', null, null, null, null, ':', $academic->year],
                ['Semester', null, null, null, null, ':', $academic->semester],
                ['Kompetensi', null, null, null, null, ':', $competency->description],
            ];
            $sheet->fromArray($identitas);
            
            // kosongkan datanya
            $data = [];
            $data[] = [
                'nis','nama siswa','teacher_subject_id','student_id','competency_id','score'
            ];

            foreach ($competency->studentCompetency as $studentCompetency) {
                $data[] = [
                    $studentCompetency->student->nis,
                    $studentCompetency->student->name,
                    $studentCompetency->teacher_subject_id,
                    $studentCompetency->student_id,
                    $studentCompetency->competency_id,
                    $studentCompetency->score,
                ];
            }

            $sheet->fromArray($data,null,'A10', true);

            $countSheet++;
            
            $sheet->getColumnDimension('B')->setWidth(30);
    
            // hide coloumn C D E
            $sheet->getColumnDimension('C')->setVisible(false);
            $sheet->getColumnDimension('D')->setVisible(false);
            $sheet->getColumnDimension('E')->setVisible(false);
        }


        // hapus sheet worksheet
        $sheetIndex = $spreadsheet->getIndex(
            $spreadsheet->getSheetByName('Worksheet')
        );
        $spreadsheet->removeSheetByIndex($sheetIndex);

        // Membuat file Excel
        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "studentCompetency-".$subject->code.".xlsx"; // <<< HERE
        $writer->save(base_path($filename));
        return response()->download(base_path($filename)); // <<< HERE
    }

    public function attendance($grade_id)
    {
        $academic = AcademicYear::active()->first()->id;
        $grade = Grade::with('studentGrade')->find($grade_id);
        $students = $grade->studentGrade->pluck('student');
        
        // Inisialisasi spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Sheet1');
        $sheet->setCellValue('A1', 'DAFTAR HADIR');

        $data = [];

        $data[] = [
            'nis',
            'nama siswa',
            'academic_year_id',
            'grade_id',
            'student_id',
            'sakit',
            'izin',
            'tanpa_keterangan',
            'catatan',
            'penghargaan',
        ];

        foreach ($students as $student) {
            $sick = 0;
            $permission = 0;
            $absent = 0;
            $note = "";
            $achievement = "";

            if($student->attendance){
                $sick = $student->attendance->sick;
                $permission = $student->attendance->permission;
                $absent = $student->attendance->absent;
                $note = $student->attendance->note;
                $achievement = $student->attendance->achievement;
            } else {
                $sick = 0;
                $permission = 0;
                $absent = 0;
                $note = '-';
                $achievement = '-';
            }

            $data[] = [
                $student->nis,
                $student->name,
                $academic,
                $grade_id,
                $student->id,
                $sick,
                $permission,
                $absent,
                $note,
                $achievement,
            ];
        }

        $sheet->fromArray($data, null, 'A3', true);

        $sheet->getColumnDimension('B')->setWidth(30);
    
        // hide coloumn C D E
        $sheet->getColumnDimension('C')->setVisible(false);
        $sheet->getColumnDimension('D')->setVisible(false);
        $sheet->getColumnDimension('E')->setVisible(false);

        // Membuat file Excel
        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "attendance-".$grade->name.".xlsx"; // <<< HERE
        $writer->save(base_path($filename));
        return response()->download(base_path($filename)); // <<< HERE
    }

    public function studentExtracurricular()
    {
        $spreadsheet = new Spreadsheet();
        
        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('StudentExtracurricular');
        $sheet1->setCellValue('A1', 'extracurricular_id');
        $sheet1->setCellValue('B1', 'student_id');
        $rowStudentExtra = 2;

        $studentExtras = StudentExtracurricular::all();
        foreach ($studentExtras as $studentExtra) {
            $sheet1->setCellValue('A'.$rowStudentExtra, $studentExtra->extracurricular_id);
            $sheet1->setCellValue('B'.$rowStudentExtra, $studentExtra->student_id);
            $rowStudentExtra++;
        }

        // membuat sheet kedua
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->getSheet(1); // Indeks dimulai dari 0
        $sheet2->setTitle('Student');
        $sheet2->setCellValue('A1', 'student_id');
        $sheet2->setCellValue('B1', 'nama_lengkap');
        $sheet2->setCellValue('C1', 'gender');
        $sheet2->setCellValue('d1', 'nis');
        $sheet2->setCellValue('e1', 'nisn');
        $rowStudent = 2;

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
        $sheet3->setTitle('Extracurricular');
        $sheet3->setCellValue('A1', 'extracurricular_id');
        $sheet3->setCellValue('B1', 'extracurricular');
        $rowExtra = 2;

        $extras = Extracurricular::all();
        foreach ($extras as $extra) {
            $sheet3->setCellValue('A'.$rowExtra, $extra->id);
            $sheet3->setCellValue('B'.$rowExtra, $extra->name);
            $rowExtra++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "studentExtracurricular.xlsx"; // <<< HERE
        $writer->save(base_path($filename));
        return response()->download(base_path('studentExtracurricular.xlsx')); // <<< HERE
    }

    public function teacherExtracurricular()
    {
        $spreadsheet = new Spreadsheet();
        
        // Membuat lembar pertama
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('TeacherExtracurricular');
        $sheet1->setCellValue('A1', 'extracurricular_id');
        $sheet1->setCellValue('B1', 'teacher_id');
        $rowStudentExtra = 2;

        $teacherExtras = TeacherExtracurricular::all();
        foreach ($teacherExtras as $teacherExtra) {
            $sheet1->setCellValue('A'.$rowStudentExtra, $teacherExtra->extracurricular_id);
            $sheet1->setCellValue('B'.$rowStudentExtra, $teacherExtra->teacher_id);
            $rowStudentExtra++;
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
        $sheet3->setTitle('Extracurricular');
        $sheet3->setCellValue('A1', 'extracurricular_id');
        $sheet3->setCellValue('B1', 'extracurricular');
        $rowExtra = 2;

        $extras = Extracurricular::all();
        foreach ($extras as $extra) {
            $sheet3->setCellValue('A'.$rowExtra, $extra->id);
            $sheet3->setCellValue('B'.$rowExtra, $extra->name);
            $rowExtra++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); // <<< HERE
        $filename = "teacherExtracurricular.xlsx"; // <<< HERE
        $writer->save(base_path($filename));
        return response()->download(base_path('teacherExtracurricular.xlsx')); // <<< HERE
    }
}
