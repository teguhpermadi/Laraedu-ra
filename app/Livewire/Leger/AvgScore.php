<?php

namespace App\Livewire\Leger;

use App\Models\Competency;
use App\Models\StudentCompetency;
use Livewire\Component;

class AvgScore extends Component
{
    public $score, $color;

    public function mount($student_id, $teacher_subject_id, $column = 'score')
    {
        $data = StudentCompetency::where('student_id', $student_id)->where('teacher_subject_id', $teacher_subject_id)->get();
        
        if($column == 'score_skill'){
            $this->score = round($data->avg('score_skill'), 3);
        } else {
            $this->score = round($data->avg('score'), 3);
        }

        $this->color = ($this->score < 70) ? 'yellow' : '';
    }

    public function render()
    {
        return view('livewire.leger.avg-score');
    }
}
