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

        $middle = Exam::where('student_id', $student_id)->where('teacher_subject_id', $teacher_subject_id)->where('category', 'middle')->first();
        $last = Exam::where('student_id', $student_id)->where('teacher_subject_id', $teacher_subject_id)->where('category', 'last')->first();

         // Pengecekan jika $middle atau $last null
        $middleScore = $middle ? $middle->score : null;
        $lastScore = $last ? $last->score : null;

        $data = collect([$avg_score_competency, $middleScore, $lastScore]);
        
        $this->score = $data->avg();
        $this->color = ($this->score < 70) ? 'yellow' : '';
    }

    public function render()
    {
        return view('livewire.leger.na-score');
    }
}
