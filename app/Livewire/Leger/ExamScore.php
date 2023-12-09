<?php

namespace App\Livewire\Leger;

use App\Models\Exam;
use Livewire\Component;

class ExamScore extends Component
{
    public $score, $color;

    public function mount($student_id, $teacher_subject_id, $caterogy)
    {
        $data = Exam::where('student_id', $student_id)
            ->where('teacher_subject_id', $teacher_subject_id)
            ->first();
        
        if($caterogy == 'middle'){
            // score middle
            $this->score = ($data) ? $data->score_middle : null;
        } else {
            $this->score = ($data) ? $data->score_last : null;
        }
        
        $this->color = ($this->score < 70) ? 'yellow' : '';

    }

    public function render()
    {
        return view('livewire.leger.exam-score');
    }
}
