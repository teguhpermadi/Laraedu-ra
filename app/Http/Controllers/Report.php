<?php

namespace App\Http\Controllers;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\StudentGrade;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;

class Report extends Controller
{
    public function calculateReport($id)
    {
        $data = [];

        $academic = AcademicYear::active()->first();
        
        $student = Student::find($id);

        $grade = StudentGrade::with('grade')->where('student_id', $id)->first();

        $scores = Student::with([
            'studentGrade.teacherSubject.studentCompetency' => function($q) use ($id){
            $q->where('student_id',$id)->result();
            }])->find($id);
        
        $subjects = $scores->studentGrade->teacherSubject;
    
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
                $combinedResultDescription = $lulusDescription . ' tetapi, ' . $tidakLulusDescription;
            } elseif($lulusDescription) {
                $combinedResultDescription = $lulusDescription;
            } else {
                $combinedResultDescription = $tidakLulusDescription;
            }
    
            $result[$subject->subject->code] = [
                // 'teacher_subject_id' => $subject->id,
                'subject' => $subject->subject->name,
                'code' => $subject->subject->code,
                'score' => round($subject->studentCompetency->avg('score')),
                'combined_result_description' => $combinedResultDescription,
            ];

        }

        $data = [
            'academic' => $academic->toArray(),
            'student' => $student->toArray(),
            'grade' => $grade->grade->toArray(),
            'result' => $result,
        ];

        return $data;

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
            $table->addCell()->addText($item["score"]);
            $table->addCell()->addText($item["combined_result_description"]);
            $nomorUrut++;
        }

        $templateProcessor->setComplexBlock('table', $table);
        
        $pathToSave = 'raport '.$data['student']['name'].'.docx';
        
        $templateProcessor->saveAs($pathToSave);
    }
}
