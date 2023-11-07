<?php

use App\Events\CalculateReport;
use App\Exports\CompetencyExport;
use App\Http\Controllers\ExportExcel;
use App\Http\Controllers\Report;
use App\Http\Controllers\StudentCompetencyExcel;
use App\Imports\CompetencyImport;
use App\Imports\ExcelUtils;
use App\Imports\StudentCompetencyImport;
use App\Imports\StudentCompetencySheetImport;
use App\Imports\StudentGradeImport;
use App\Imports\StudentImport;
use App\Imports\TeacherGradeImport;
use App\Imports\TeacherGradeSheetImport;
use App\Imports\TeacherImport;
use App\Models\Student;
use App\Models\StudentCompetency;
use App\Models\Teacher;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('tes', function(){
    // Excel::import(new StudentImport, storage_path('/app/public/uploads/siswa.xlsx'));
    // return '1';
    $competencies = Excel::toCollection(new CompetencyImport, storage_path('/app/public/uploads/kompetensi.xlsx'));
    
    $data = [];
    
    foreach ($competencies[0] as $competency) {
        $data[] = [
            'description' => $competency[0],
            'passing_grade' => $competency[1],
        ];
    }
    array_shift($data);
    dd($data);
});

Route::get('result_old/{id}', function($id){
    $student = Student::with(
        'studentGrade.grade',
        'studentGrade.teacherSubject.subject',
        )->find($id);

    $scores = Student::with([
        'studentGrade.teacherSubject.studentCompetency' => function($q) use ($id){
        $q->where('student_id',$id)->result();
        }])->find($id);
    
    $subjects = $scores->studentGrade->teacherSubject;

    foreach ($subjects as $subject) {
        //$resultDescriptions = [];
        $lulusDescriptions = [];
        $tidakLulusDescriptions = [];

        foreach ($subject->studentCompetency as $competency) {
            //$resultDescriptions[] = $competency['result_description'];
            if ($competency['result'] === "LULUS") {
                $lulusDescriptions[] = $competency['result_description'];
            } else {
                $tidakLulusDescriptions[] = $competency['result_description'];
            }
        }

        // Gabungkan result_description untuk "LULUS" dan "TIDAK LULUS"
        $lulusDescription = implode(", ", $lulusDescriptions);
        
        $tidakLulusDescription = implode(", ", $tidakLulusDescriptions);
        
        if($lulusDescription && $tidakLulusDescription){
            $combinedResultDescription = $lulusDescription . ' tetapi, ' . $tidakLulusDescription;
        } elseif($lulusDescription) {
            $combinedResultDescription = $lulusDescription;
        } else {
            $combinedResultDescription = $tidakLulusDescription;
        }

        $result = [
            $subject->subject->code => [
                'teacher_subject_id' => $subject->id,
                'subject' => $subject->subject->name,
                'code' => $subject->subject->code,
                'score' => $subject->studentCompetency->avg('score'),
                'combined_result_description' => $combinedResultDescription,
            ],
        ];

        $student->studentGrade->teacherSubject->push($result);
    }
    
    dd($student) ;
})->name('result_old'); 

// Route::get('export', function(){
//     // Excel::download(new CompetencyExport, 'competency.xlsx', \Maatwebsite\Excel\Excel::XLSX);
//     return Excel::download(new CompetencyExport, 'competency.xlsx', \Maatwebsite\Excel\Excel::XLSX);
//     // return '1';
// });

// Route::get('result/{id}', function($id){
//     event(new CalculateReport($id));
// })->name('result');

Route::controller(Report::class)->group(function(){
    Route::get('report/{id}', 'calculateReport')->name('report');
});

Route::controller(StudentCompetencyExcel::class)->group(function(){
    Route::get('getdata/{teacher_subject_id}', 'getData')->name('studentCompetencyExcel.getData');
});

Route::get('import', function(){
    $studentCompetencies = Excel::toArray(new StudentCompetencyImport, storage_path('/app/public/uploads/studentCompetencySheet.xlsx'));
    
    $data = [];
    foreach ($studentCompetencies as $row) {
        foreach ($row as $value) {
            StudentCompetency::where([
                'teacher_subject_id' => $value['teacher_subject_id'],
                'student_id' => $value['student_id'],
                'competency_id' => $value['competency_id'],
            ])
            ->update(['score' => $value['score']]);
        }
    }

});

Route::controller(ExportExcel::class)->group(function(){
    Route::get('export/student-grade', 'studentGrade')->name('export.studentGrade');
    Route::get('export/teacher-subject', 'teacherSubject')->name('export.teacherSubject');
    Route::get('export/teacher-grade', 'teacherGrade')->name('export.teacherGrade');
    Route::get('export/student-competency/{teacher_subject_id}', 'studentCompetency')->name('export.studentCompetency');
    Route::get('export/student-competency-sheet/{teacher_subject_id}', 'studentCompetencySheet')->name('export.studentCompetencySheet');
});