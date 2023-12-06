<?php

namespace App\Filament\Resources\ProjectTargetResource\Pages;

use App\Filament\Resources\ProjectTargetResource;
use App\Models\Project;
use App\Models\ProjectStudent;
use App\Models\ProjectTarget;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;

class Assesment extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ProjectTargetResource::class;

    protected static string $view = 'filament.resources.project-target-resource.pages.assesment';

    public ?array $data = [];
    public $capaian;

    public function mount($record)
    {
        $project = ProjectTarget::find($record);
        $phase = $project->project->grade->fase;
        $data = ProjectTarget::with(['subElement.target' => function($q)use($phase){
            $q->phase($phase);
        }, ])->find($record);
        
        // dd($data->toArray());
        foreach ($data->subElement as $item) {
            $this->data['capaian'][] = [
                'student_id' => 'tes',
                'capaian_description' => $item->target->description,
            ];
        }
        
        // $students = ProjectStudent::with('student')->where('project_target_id', $record)->get();
        // $this->data['capaian'] = [
        //     ['student_id' => '1', 'name' => 'teguh permadi'],
        //     ['student_id' => '2', 'name' => 'teguh permadi'],
        // ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make('capaian')
                    ->schema([
                        Textarea::make('capaian_description')->disabled(),
                        TextInput::make('student_id'),
                        Repeater::make('students')
                        ->schema([
                            
                        ]),
                    ])
                    ->reorderableWithDragAndDrop(false)
                    ->deletable(false)
                    ->addable(false),
                    // ->itemLabel(fn (array $state): ?string => $state['capaian_description'] ?? null),
            ]);
    }

    public function submit()
    {
        dd($this->form->getState());
    }
}
