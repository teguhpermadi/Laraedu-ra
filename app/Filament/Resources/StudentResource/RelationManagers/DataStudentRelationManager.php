<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataStudentRelationManager extends RelationManager
{
    protected static string $relationship = 'dataStudent';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('religion')->options([
                    'islam' => 'Islam',
                    'kristen' => 'Kristen',
                    'katholik' => 'Katholik',
                    'hindu' => 'Hindu',
                    'budha' => 'Budha',
                    'konghuchu' => 'Konghuchu',
                ]),
                TextInput::make('previous_school'),
                DatePicker::make('date_received')->format('Y-m-d'),
                TextInput::make('grade_received'),
                TextInput::make('father_education'),
                TextInput::make('father_occupation'),
                TextInput::make('father_phone'),
                TextInput::make('mother_name'),
                TextInput::make('mother_education'),
                TextInput::make('mother_occupation'),
                TextInput::make('mother_phone'),
                TextInput::make('guardian_name'),
                TextInput::make('guardian_education'),
                TextInput::make('guardian_occupation'),
                TextInput::make('guardian_phone'),
                TextInput::make('guardian_address'),
                TextInput::make('guardian_village'),
                TextInput::make('parent_address'),
                TextInput::make('parent_village'),
                TextInput::make('height')->numeric()->inputMode('decimal')->suffix('cm'),
                TextInput::make('weight')->numeric()->inputMode('decimal')->suffix('Kg'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('dataStudent')
            ->columns([
                Tables\Columns\TextColumn::make('religion'),
                Tables\Columns\TextColumn::make('mother_name'),
                Tables\Columns\TextColumn::make('father_name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->hidden(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->hidden(),
                ]),
            ]);
    }
}
