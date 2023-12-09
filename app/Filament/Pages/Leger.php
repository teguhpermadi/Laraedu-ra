<?php

namespace App\Filament\Pages;

use App\Models\TeacherSubject;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Leger extends Page implements HasTable
{
    use HasPageShield;
    use InteractsWithTable;

    protected static ?string $title = 'Leger Subject';
    
    protected static ?string $navigationGroup = 'Teacher';
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.leger';

    public function table(Table $table): Table
    {
        return $table
            ->query(TeacherSubject::query()->mySubject())
            ->columns([
                TextColumn::make('subject.name'),
                TextColumn::make('grade.name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Action::make('leger')
                    ->button()
                    ->url(fn (TeacherSubject $record): string => route('leger.subject', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
