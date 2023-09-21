<?php

namespace App\Filament\Resources\SubjectResource\Pages;

use App\Filament\Resources\SubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListSubjects extends ListRecords
{
    protected static string $resource = SubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        if(auth()->user()->userable){
            return [
                'all_subject' => Tab::make('All Subjects'),
                'my_subject' => Tab::make('My Subject')
                    ->modifyQueryUsing(function($query){
                        return $query->teacher();
                }),
            ];
        } else {
            return [
                'all_subject' => Tab::make('All Subjects'),
            ];
        }
    }
}
