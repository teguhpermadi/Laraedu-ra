<?php

namespace App\Livewire\Leger;

use App\Models\Competency;
use App\Models\StudentCompetency;
use Livewire\Component;

class Score extends Component
{
    public $score, $color;

    public function mount($student_id, $competency_id, $column = 'score')
    {
        $data = StudentCompetency::where('student_id', $student_id)->where('competency_id', $competency_id)->first();
        $competency = Competency::find($competency_id);

        if($column == 'score_skill'){
            $score = $data->score_skill;
        } else {
            $score = $data->score;
        }

        switch ($score) {
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
        
        $this->color = ($score < $competency->passing_grade) ? 'yellow' : '';
    }

    public function render()
    {
        return view('livewire.leger.score');
    }
}
