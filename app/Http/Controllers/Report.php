<?php

namespace App\Http\Controllers;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Attitude;
use App\Models\Exam;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentExtracurricular;
use App\Models\StudentGrade;
use App\Models\TeacherGrade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use Illuminate\Support\Str;
use Spatie\Valuestore\Valuestore;

class Report extends Controller
{
    public function calculateReport($id)
    {
        $data = [];
        $school = School::first();

        $academic = AcademicYear::with('teacher')->active()->first();

        $student = Student::with('dataStudent')->find($id);
        
        $grade = StudentGrade::with('grade')->where('student_id', $id)->first();
        $teacherGrade = TeacherGrade::with('teacher')->where('grade_id', $grade->grade_id)->first();

        $attendance = Attendance::where('student_id', $id)->first();
        
        $attitude = Attitude::where('student_id', $id)->first();

        $extracurriculars = StudentExtracurricular::where('student_id', $id)->description()->get();

        $scores = Student::with([
            'studentGrade.teacherSubject.studentCompetency' => function($q) use ($id){
                $q->where('student_id',$id)->result();
            }])->find($id);

        $subjects = $scores->studentGrade->teacherSubject;
        
        $result = [];

        foreach ($subjects as $subject) {
            // buat dulu deskripsinya
            $combinedResultDescription = 'Alhamdulillah ananda ' . Str::of($student->nick_name)->title() . ' telah menunjukkan perkembangan yang baik di semester ini. ';
            $sangatBaik = [];
            $baik = [];
            $tingkatkan = [];
            $dataScore = [];
            $predicate = '';

            foreach ($subject->studentCompetency as $competency) {
                switch ($competency['score']) {
                    case '4':
                        $sangatBaik[] = $competency['description'];
                        break;
                    case '3':
                        $baik[] = $competency['description'];
                        break;
                    
                    default:
                        $tingkatkan[] = $competency['description'];
                        break;
                }

                $dataScore[] = $competency['predicate_desc'];
            }

            $sangatBaikDescription = implode('; ', $sangatBaik);
            $baikDescription = implode('; ', $baik);
            $tingkatkanDescription = implode('; ', $tingkatkan);

            if($sangatBaikDescription){
                $combinedResultDescription .= 'Ananda sudaH SANGAT BERKEMBANG dalam:' . $sangatBaikDescription;
                if($baikDescription){
                    $combinedResultDescription .= '</w:t><w:p/><w:t></w:t><w:p/><w:t> Ananda juga ';
                }
                // if($tingkatkan){
                //     $combinedResultDescription .= '</w:t><w:p/><w:t></w:t><w:p/><w:t> Diharapkan pada semester selanjutnya ananda dapat mempertahankan kemampuannya dan lebih meningkatkan diri dalam: ';
                // }
            } 
            
            if ($baikDescription) {
                $combinedResultDescription .= 'Ananda sudah BERKEMBANG SESUAI HARAPAN dalam: '. $baikDescription . '. ';
                // if($tingkatkanDescription){
                //     $combinedResultDescription .= '</w:t><w:p/><w:t></w:t><w:p/><w:t> Diharapkan pada semester selanjutnya ananda dapat mempertahankan kemampuannya dan lebih meningkatkan diri dalam: ';
                // }
            } 

            if($tingkatkanDescription) {
                $combinedResultDescription .= '</w:t><w:p/><w:t></w:t><w:p/><w:t> Diharapkan pada semester selanjutnya ananda dapat mempertahankan kemampuannya dan lebih meningkatkan diri dalam: ' . $tingkatkanDescription ;
            }

            // cek deskripsi
            $result[] = [
                'order' => $subject->subject->order,
                'subject' => $subject->subject->name,
                'code' => $subject->subject->code,
                'predicate' => $this->getMode($dataScore)[0],
                'combined_description' => $combinedResultDescription,
            ];

        }
        

        $resultOrder = collect($result)->sortBy('order')->values()->all();

        $resultCollection = collect($result);
        $totalAverageScore = $resultCollection->sum('average_score');
        // $totalAverageScoreSkill = $resultCollection->sum('average_score_skill');
        $counting_total = readNumber($totalAverageScore);
        // $counting_total_skill = readNumber($totalAverageScoreSkill);

        $extra = [];
        $numExtra = 1;
        foreach ($extracurriculars as $extracurricular) {
            $extra [] = [
                'orderEx' => $numExtra,
                'name' => $extracurricular->name,
                'score' => $extracurricular->score,
                'description' => $extracurricular->description,
            ];

            $numExtra++;
        }

        App::setLocale('id');

        $data = [
            'school' => $school,
            'academic' => $academic->toArray(),
            'headmaster' => $academic->teacher->name,
            'date_report' => Carbon::parse($academic->date_report)->isoFormat('D MMMM Y'),
            'teacher' => $teacherGrade->teacher,
            'student' => $student->toArray(),
            'grade' => $grade->grade->toArray(),
            'attendance' => $attendance,
            'attitude' => $attitude,
            'result' => $resultOrder,
            'total_average_score' => $totalAverageScore,
            'counting_total' => $counting_total,
            'extracurriculars' => $extra,
        ];

        // $data = $this->report($data);
        // switch ($teacherGrade->curriculum) {
        //     case '2013':
        //         $data = $this->report2013($data);
        //         break;
            
        //     default:
        //         $data = $this->report($data);
        //         break;
        // }
        $data = $this->report($data);
        return $data;
    }

    public function report($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/reportra.docx'));
        $templateProcessor->setValue('school_name',$data['school']['name']);
        $templateProcessor->setValue('school_address',$data['school']['address']);
        $templateProcessor->setValue('headmaster',$data['headmaster']);
        $templateProcessor->setValue('date_report',$data['date_report']);
        $templateProcessor->setValue('year',$data['academic']['year']);
        $templateProcessor->setValue('semester',$data['academic']['semester']);
        $templateProcessor->setValue('student_name',$data['student']['name']);
        $templateProcessor->setValue('nisn',$data['student']['nisn']);
        $templateProcessor->setValue('nis',$data['student']['nis']);
        $templateProcessor->setValue('grade_name',$data['grade']['name']);
        $templateProcessor->setValue('grade_level',$data['grade']['fase']);
        $templateProcessor->setValue('sick',$data['attendance']['sick']);
        $templateProcessor->setValue('permission',$data['attendance']['permission']);
        $templateProcessor->setValue('absent',$data['attendance']['absent']);
        $templateProcessor->setValue('total_attendance',$data['attendance']['total_attendance']);
        $templateProcessor->setValue('note',$data['attendance']['note']);
        $templateProcessor->setValue('achievement',$data['attendance']['achievement']);
        $templateProcessor->setValue('attitude_religius',$data['attitude']['attitude_religius']);
        $templateProcessor->setValue('attitude_social',$data['attitude']['attitude_social']);
        $templateProcessor->setValue('teacher_name',$data['teacher']['name']);
        $templateProcessor->setValue('total_average_score',$data['total_average_score']);
        $templateProcessor->setValue('counting_total',$data['counting_total']);
        $templateProcessor->setValue('height',$data['student']['data_student']['height']);
        $templateProcessor->setValue('weight',$data['student']['data_student']['weight']);
        $templateProcessor->setValue('anm',$data['result'][0]['combined_description']);
        $templateProcessor->setValue('jd',$data['result'][1]['combined_description']);
        $templateProcessor->setValue('dls',$data['result'][2]['combined_description']);

        // tabel nilai mata pelajaran
        // $templateProcessor->cloneRowAndSetValues('order', $data['result']);

        // tabel ekstrakurikuler
        $templateProcessor->cloneRowAndSetValues('orderEx', $data['extracurriculars']);
        
        $filename = 'Rapor '.$data['student']['name'].' - '. $data['academic']['semester'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true);; // <<< HERE
    }

    public function getData($id)
    {
        $academic = AcademicYear::with('teacher')->active()->first();
        $student = Student::with('dataStudent')->find($id);
        $data = [
            'student' => $student,
            'academic' => $academic,
        ];
        
        $data = $this->coverStudent($data);
        return $data;
    }

    public function coverStudent($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/cover-student.docx'));
        $templateProcessor->setValue('nama',$data['student']['name']);
        $templateProcessor->setValue('nisn',$data['student']['nisn']);
        $templateProcessor->setValue('nis',$data['student']['nis']);
        $templateProcessor->setValue('tempat_lahir',$data['student']['city_born']);
        // $templateProcessor->setValue('tanggal_lahir',$data['student']['birthday']);
        $templateProcessor->setValue('tanggal_lahir', Carbon::createFromFormat('Y-m-d', $data['student']['birthday'])->locale('id')->translatedFormat('d F Y'));
        $templateProcessor->setValue('jenis_kelamin',$data['student']['gender']);
        $templateProcessor->setValue('agama',$data['student']['dataStudent']['religion']);
        $templateProcessor->setValue('pendidikan_sebelumnya',$data['student']['dataStudent']['previous_school']);
        $templateProcessor->setValue('alamat',$data['student']['dataStudent']['student_address']);
        $templateProcessor->setValue('kelurahan',$data['student']['dataStudent']['student_village']);
        $templateProcessor->setValue('kecamatan',$data['student']['dataStudent']['student_district']);
        $templateProcessor->setValue('kota',$data['student']['dataStudent']['student_city']);
        $templateProcessor->setValue('provinsi',$data['student']['dataStudent']['student_province']);
        
        // ayah
        $templateProcessor->setValue('nama_ayah',$data['student']['dataStudent']['father_name']);
        $templateProcessor->setValue('pendidikan_ayah',$data['student']['dataStudent']['father_education']);
        $templateProcessor->setValue('pekerjaan_ayah',$data['student']['dataStudent']['father_occupation']);
        // ibu
        $templateProcessor->setValue('nama_ibu',$data['student']['dataStudent']['mother_name']);
        $templateProcessor->setValue('pendidikan_ibu',$data['student']['dataStudent']['mother_education']);
        $templateProcessor->setValue('pekerjaan_ibu',$data['student']['dataStudent']['mother_occupation']);
        // alamat
        $templateProcessor->setValue('alamat_orangtua',$data['student']['dataStudent']['parent_address']);
        $templateProcessor->setValue('kelurahan_orangtua',$data['student']['dataStudent']['parent_village']);
        $templateProcessor->setValue('kecamatan_orangtua',$data['student']['dataStudent']['parent_district']);
        $templateProcessor->setValue('kota_orangtua',$data['student']['dataStudent']['parent_city']);
        $templateProcessor->setValue('provinsi_orangtua',$data['student']['dataStudent']['parent_province']);
        // wali
        $templateProcessor->setValue('nama_wali',$data['student']['dataStudent']['guardian_name']);
        $templateProcessor->setValue('pekerjaan_wali',$data['student']['dataStudent']['guardian_occupation']);
        $templateProcessor->setValue('alamat_wali',$data['student']['dataStudent']['guardian_address']);

        // tanda tangan
        $templateProcessor->setValue('date_received', Carbon::createFromFormat('Y-m-d', $data['student']['dataStudent']['date_received'])->locale('id')->translatedFormat('d F Y'));
        $templateProcessor->setValue('headmaster',$data['academic']['teacher']['name']);

        $filename = 'Identitas '.$data['student']['name'].' - '. $data['academic']['semester'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    public function getDataCover($id)
    {
        $student = Student::find($id);
        
        $data = $this->cover($student);
        return $data;
    }

    public function cover($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/cover.docx'));
        $templateProcessor->setValue('nama',$data['name']);
        $templateProcessor->setValue('nisn',$data['nisn']);
        $templateProcessor->setValue('nis',$data['nis']);

        $filename = 'Cover '.$data['name'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true); // <<< HERE
    }

    /**
     * kurikulum 2013
     */

    public function report2013($data)
    {
        $templateProcessor = new TemplateProcessor( storage_path('/app/public/templates/report2013.docx'));
        $templateProcessor->setValue('school_name',$data['school']['name']);
        $templateProcessor->setValue('school_address',$data['school']['address']);
        $templateProcessor->setValue('headmaster',$data['headmaster']);
        $templateProcessor->setValue('date_report',$data['date_report']);
        $templateProcessor->setValue('year',$data['academic']['year']);
        $templateProcessor->setValue('semester',$data['academic']['semester']);
        $templateProcessor->setValue('student_name',$data['student']['name']);
        $templateProcessor->setValue('nisn',$data['student']['nisn']);
        $templateProcessor->setValue('nis',$data['student']['nis']);
        $templateProcessor->setValue('grade_name',$data['grade']['name']);
        $templateProcessor->setValue('grade_level',$data['grade']['grade']);
        $templateProcessor->setValue('sick',$data['attendance']['sick']);
        $templateProcessor->setValue('permission',$data['attendance']['permission']);
        $templateProcessor->setValue('absent',$data['attendance']['absent']);
        $templateProcessor->setValue('total_attendance',$data['attendance']['total_attendance']);
        $templateProcessor->setValue('note',$data['attendance']['note']);
        $templateProcessor->setValue('achievement',$data['attendance']['achievement']);
        $templateProcessor->setValue('attitude_religius',$data['attitude']['attitude_religius']);
        $templateProcessor->setValue('attitude_social',$data['attitude']['attitude_social']);
        $templateProcessor->setValue('teacher_name',$data['teacher']['name']);
        $templateProcessor->setValue('total_average_score',$data['total_average_score']);
        $templateProcessor->setValue('counting_total',$data['counting_total']);
        $templateProcessor->setValue('total_average_score_skill',$data['total_average_score_skill']);
        $templateProcessor->setValue('counting_total_skill',$data['counting_total_skill']);

        // tabel nilai mata pelajaran
        $templateProcessor->cloneRowAndSetValues('order', $data['result']);
        $templateProcessor->cloneRowAndSetValues('orderDes', $data['result']);

        // tabel ekstrakurikuler
        $templateProcessor->cloneRowAndSetValues('orderEx', $data['extracurriculars']);
        
        $filename = 'Rapor '.$data['student']['name'].' - '. $data['academic']['semester'] .'.docx';
        $file_path = storage_path('/app/public/downloads/'.$filename);
        $templateProcessor->saveAs($file_path);
        return response()->download($file_path)->deleteFileAfterSend(true);; // <<< HERE
    }

    function getMode($arrInput){ 
        if(is_array($arrInput)){ 
 
            // Find the frequency of occurrence of unique numbers in the array 
            $arrFrequency = [];
            foreach( $arrInput as $v ) {
                if (!isset($arrFrequency[$v])) {
                    $arrFrequency[$v] = 0;
                }
                $arrFrequency[$v]++;
            }
 
            // If there is no mode, then return empty array
            if( count($arrInput) == count($arrFrequency) ){
                return []; 
            }
 
            // Get only the modes
            $arrMode = array_keys($arrFrequency, max($arrFrequency));
 
            return $arrMode; 
        }     
    }
}
