<?php

namespace App\Livewire\Leger;

use App\Models\Exam;
use App\Models\StudentCompetency;
use Livewire\Component;

class NaScore extends Component
{
    public $score, $color;

    public function mount($student_id, $teacher_subject_id, $column = 'score')
    {
        $score_competency = StudentCompetency::where('student_id', $student_id)->where('teacher_subject_id', $teacher_subject_id)->get();
        
        if($column == 'score_skill'){
            $avg_score_competency = round($score_competency->avg('score_skill'), 1);
        } else {
            $avg_score_competency = round($score_competency->avg('score'), 1);
        }
        
        $exam = Exam::where('student_id', $student_id)->where('teacher_subject_id', $teacher_subject_id)->first();
        $middle = $exam->score_middle;
        $last = $exam->score_last;

         // Pengecekan jika $middle atau $last null
        $middleScore = $middle ? $middle : null;
        $lastScore = $last ? $last : null;

        if($column == 'score'){
            // jika nilai pengetahuan maka gabungkan nilai competency + middle + score
            $data = collect([$avg_score_competency, $middleScore, $lastScore]);
        } else {
            // jika nilai keterampilan maka hanya nilai competency
            $data = collect([$avg_score_competency]);
        }
        
        $this->score = round($data->avg(),2);
        $this->color = ($this->score < 70) ? 'yellow' : '';
    }

    public function render()
    {
        return view('livewire.leger.na-score');
    }
}
