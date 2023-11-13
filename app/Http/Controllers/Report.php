<?php

namespace App\Http\Controllers;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Student;
use App\Models\StudentExtracurricular;
use App\Models\StudentGrade;
use App\Models\TeacherGrade;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use Illuminate\Support\Str;

class Report extends Controller
{
    public function calculateReport($id)
    {
        $data = [];

        $academic = AcademicYear::active()->first();

        $student = Student::find($id);
        
        $grade = StudentGrade::with('grade')->where('student_id', $id)->first();
        $teacherGrade = TeacherGrade::with('teacher')->where('grade_id', $grade->grade_id)->first();

        $attendance = Attendance::where('student_id', $id)->first();

        $extracurricular = StudentExtracurricular::where('student_id', $id)->first();

        $scores = Student::with([
            'studentGrade.teacherSubject.studentCompetency' => function($q) use ($id){
                $q->where('student_id',$id)->result();
            }])->find($id);
        
        $subjects = $scores->studentGrade->teacherSubject;
        
        $result = [];
        foreach ($subjects as $subject) {
            // buat dulu deskripsinya
            $lulusDescriptions = [];
            $tidakLulusDescriptions = [];
    
            foreach ($subject->studentCompetency as $competency) {
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
                $combinedResultDescription = 'Alhamdulillah ananda '. Str::of($student->nick_name)->title()  . ' ' .$lulusDescription . ' tetapi, ' . $tidakLulusDescription;
            } elseif($lulusDescription) {
                $combinedResultDescription = $lulusDescription;
            } else {
                $combinedResultDescription = $tidakLulusDescription;
            }

            $middle = Exam::where('category', 'middle')->where('teacher_subject_id',$subject->id)->where('student_id', $id)->first();
            $last = Exam::where('category', 'last')->where('teacher_subject_id',$subject->id)->where('student_id', $id)->first();

            // Pengecekan jika $middle atau $last null
            $middleScore = $middle ? $middle->score : null;
            $lastScore = $last ? $last->score : null;

            $avg_score_student_competencies = $subject->studentCompetency->avg('score');

            $scores = collect([$avg_score_student_competencies, $middleScore, $lastScore]);
            $average_scores = $scores->avg(); 
            // dd($average_scores);
    
            $result[$subject->subject->order] = [
                // 'teacher_subject_id' => $subject->id,
                'subject' => $subject->subject->name,
                'code' => $subject->subject->code,
                'score_competencies' => round($subject->studentCompetency->avg('score'),1),
                'middle_score' => $middleScore,
                'last_score' => $lastScore,
                'average_score' => round($average_scores,1),
                'passed_description' => $lulusDescription,
                'not_pass_description' => $tidakLulusDescription,
                'combined_description' => $combinedResultDescription,
            ];

        }

        $resultCollection = collect($result);
        $totalAverageScore = $resultCollection->sum('average_score');

        $data = [
            'academic' => $academic->toArray(),
            'teacher' => $teacherGrade->teacher,
            'student' => $student->toArray(),
            'grade' => $grade->grade->toArray(),
            'attendance' => $attendance,
            'result' => $result,
            'total_average_score' => $totalAverageScore,
            'extracurricular' => $extracurricular->toArray(),
        ];

        // return $data;

        $data = $this->word($data);
    }

    public function word($data)
    {
        $templateProcessor = new TemplateProcessor('template.docx');
        $templateProcessor->setValue('year',$data['academic']['year']);
        $templateProcessor->setValue('semester',$data['academic']['semester']);
        $templateProcessor->setValue('student_name',$data['student']['name']);
        $templateProcessor->setValue('nisn',$data['student']['nisn']);
        $templateProcessor->setValue('grade_name',$data['grade']['name']);
        $templateProcessor->setValue('grade_level',$data['grade']['grade']);
        $templateProcessor->setValue('sick',$data['attendance']['sick']);
        $templateProcessor->setValue('permission',$data['attendance']['permission']);
        $templateProcessor->setValue('absent',$data['attendance']['absent']);
        $templateProcessor->setValue('total_attendance',$data['attendance']['total_attendance']);
        $templateProcessor->setValue('note',$data['attendance']['note']);
        $templateProcessor->setValue('achievement',$data['attendance']['achievement']);
        $templateProcessor->setValue('teacher_name',$data['teacher']['name']);
        $templateProcessor->setValue('extracurricular_name',$data['extracurricular']['name']);
        $templateProcessor->setValue('extracurricular_predicate',$data['extracurricular']['score']);
        $templateProcessor->setValue('extracurricular_description',$data['extracurricular']['description']);

        $table = new Table([
            'borderSize' => 6, 
            'borderColor' => 'black', 
            'unit' => TblWidth::AUTO,
        ]);

        $table->addRow();
        $table->addCell()->addText('No');
        $table->addCell()->addText('Mata Pelajaran');
        $table->addCell()->addText('Nilai');
        $table->addCell()->addText('Deskripsi');
        
        // Iterasi data dan menambahkannya ke dalam tabel
        $nomorUrut = 1;
        foreach ($data['result'] as $key => $item) {
            $table->addRow();
            $table->addCell()->addText($nomorUrut);
            $table->addCell()->addText($item["subject"]);
            $table->addCell()->addText($item["average_score"]);
            $table->addCell()->addText($item["combined_description"]);
            $nomorUrut++;
        }

        // tambahkan jumlah rata-rata score
        $table->addRow();
        $table->addCell()->addText($nomorUrut);
        $table->addCell()->addText('Jumlah Nilai');
        $table->addCell()->addText($data['total_average_score']);
        $table->addCell()->addText('membaca total rata-rata skor');

        $templateProcessor->setComplexBlock('table', $table);
        
        $pathToSave = 'raport '.$data['student']['name'].'.docx';
        $templateProcessor->saveAs($pathToSave);
    }
}
