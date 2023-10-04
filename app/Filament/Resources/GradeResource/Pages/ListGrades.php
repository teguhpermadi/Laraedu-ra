<?php

namespace App\Filament\Resources\GradeResource\Pages;

use App\Filament\Resources\GradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;

class ListGrades extends ListRecords
{
    protected static string $resource = GradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // public function getTabs(): array
    // {
    //     if(auth()->user()->userable){
    //         return [
    //             'all_grade' => Tab::make('All Grades'),
    //             'my_grade' => Tab::make('My Grade')
    //                 ->modifyQueryUsing(function($query){
    //                     return $query->teacher();
    //             }),
    //         ];
    //     } else {
    //         return [
    //             'all_grade' => Tab::make('All Grades'),
    //         ];
    //     }
    // }
}
