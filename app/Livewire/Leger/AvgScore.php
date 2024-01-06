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
        
        $mode = '';

        if($column == 'score_skill'){
            $avg = $data->avg('score_skill');
            $mode = $data->mode('score_skill');
            // $this->score = round($avg, 3);
        } else {
            $avg = $data->avg('score');
            $mode = $data->mode('score');
            // $this->score = round($mode[0], 3);
        }

        switch ($mode[0]) {
            case '4':
                $this->score = 'BSB';
                break;
            case '3':
                $this->score = 'BSH';
                break;
            case '2':
                $this->score = 'MB';
                break;
            
            default:
                $this->score = 'BB';
                break;
        }

        $this->color = ($this->score < 3) ? 'yellow' : '';
    }

    public function render()
    {
        return view('livewire.leger.avg-score');
    }
}
