<?php

use App\Exports\CompetencyExport;
use App\Imports\StudentImport;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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
    Excel::import(new StudentImport, storage_path('/app/public/uploads/siswa.xlsx'));
    return '1';
});

Route::get('word', function(){
    $file = storage_path('/app/public/templates/surat_pernyataan.rtf');
		
    $array = array(
        '[NOMOR_SURAT]' => '015/BT/SK/V/2017',
        '[PERUSAHAAN]' => 'CV. Borneo Teknomedia',
        '[NAMA]' => 'Teguh Permadi',
        '[NIP]' => '6472065508XXXX',
        '[ALAMAT]' => 'Jl. Manunggal Gg. 8 Loa Bakung, Samarinda',
        '[PERMOHONAN]' => 'Permohonan pengurusan pembuatan NPWP',
        '[KOTA]' => 'Samarinda',
        '[DIRECTOR]' => 'Noviyanto Rahmadi',
        '[TANGGAL]' => date('d F Y'),
    );

    $nama_file = 'surat-keterangan-kerja.doc';
    
    return WordTemplate::export($file, $array, $nama_file);
});

Route::get('result', function(){
    $student = Student::with(
        'studentGrade.grade',
        'studentGrade.teacherSubject.subject',
        )->find(1);

    $scores = Student::with([
        'studentGrade.teacherSubject.studentCompetency' => function($q){
        $q->where('student_id',1)->result();
        }])->find(1);
    
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
            'teacher_subject_id' => $subject->id,
            'subject' => $subject->subject->name,
            'code' => $subject->subject->code,
            'score' => $subject->studentCompetency->avg('score'),
            'combined_result_description' => $combinedResultDescription,
        ];

        $student->studentGrade->teacherSubject->push($result);
    }

    return $student;
}); 

Route::get('export', function(){
    // Excel::download(new CompetencyExport, 'competency.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    return Excel::download(new CompetencyExport, 'competency.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    // return '1';
});