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
            $this->score = $data->score_skill;
        } else {
            $this->score = $data->score;
        }
        
        $this->color = ($this->score < $competency->passing_grade) ? 'yellow' : '';
    }

    public function render()
    {
        return view('livewire.leger.score');
    }
}
