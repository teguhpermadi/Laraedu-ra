<?php

namespace App\Filament\Pages;

use App\Models\Student;
use App\Models\StudentGrade;
use App\Models\TeacherGrade;
use Filament\Tables\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class MyGrade extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.my-grade';

    public static function shouldRegisterNavigation(): bool
    {
        if(auth()->user()->hasRole('teacher grade')){
            return true;
        } else {
            return false;
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Student::query()->myStudentGrade())
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('result')
                    ->url(fn (Student $record): string => route('report', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                // ...
            ]);
    }

}
