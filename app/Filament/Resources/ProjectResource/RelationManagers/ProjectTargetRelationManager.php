<?php

namespace App\Filament\Resources\ProjectResource\RelationManagers;

use App\Models\AcademicYear;
use App\Models\Dimention;
use App\Models\Element;
use App\Models\SubElement;
use App\Models\Target;
use App\Models\Value;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectTargetRelationManager extends RelationManager
{
    protected static string $relationship = 'ProjectTarget';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('academic_year_id')->default(AcademicYear::active()->first()->id),
                Repeater::make('target')
                    ->columnSpanFull()
                    ->schema([
                        Wizard::make([
                            Step::make('Dimensi')
                            ->schema([
                                Radio::make('dimensi')
                                    ->options(Dimention::pluck('description', 'id'))
                                    ->afterStateUpdated(function($state, callable $get, callable $set){
                                        $element = $get('element');
                                        if ($element) {
                                            $set('element', null);
                                            $set('sub_element', null);
                                            $set('nilai', null);
                                            $set('sub_nilai', null);
                                        }
                                    })
                                    ->required()
                                    ->reactive(),
                            ]),
                            Step::make('Elemen')
                            ->schema([
                                Radio::make('element')
                                    ->options(function($state, callable $get, callable $set){
                                        $dimensi = $get('dimensi');
                                        if($dimensi){
                                            return Dimention::with('element')->find($dimensi)->element->pluck('description', 'id');
                                        }
                                        return [];
                                    })
                                    ->afterStateUpdated(function($state, callable $get, callable $set){
                                        $subElement = $get('sub_element');
                                        if($subElement){
                                            $set('sub_element', null);
                                            $set('sub_nilai', null);
                                        }
                                    })
                                    ->required()
                                    ->reactive(),
                            ]),
                            Step::make('Sub Elemen')
                            ->schema([
                                Radio::make('sub_element')
                                    ->options(function($state, callable $get, callable $set){
                                        $element = $get('element');
                                        if($element){
                                            return Element::with('subElement')->find($element)->subElement->pluck('description', 'id');
                                        }
                                        return [];
                                    })
                                    ->afterStateUpdated(function($state, callable $get, callable $set){
                                        $nilai = $get('nilai');
                                        if($nilai){
                                            $set('nilai', null);
                                        }

                                        // cari capaiannya
                                        $codeSubElement = SubElement::find($state)->code;
                                        $target = Target::where('code_sub_element', $codeSubElement)->first();
                                        $set('target_id', $target->toJson());
                                    })
                                    ->required()
                                    ->reactive(),
                                TextInput::make('target_id'),
                            ]),
                            Step::make('Nilai')
                            ->schema([
                                Radio::make('nilai')
                                    ->options(function($state, callable $get, callable $set){
                                        $element = $get('element');
                                        if($element){
                                            return Element::with('value')->find($element)->value->pluck('description', 'id');
                                        }
                                        return [];
                                    })
                                    ->afterStateUpdated(function($state, callable $get, callable $set){
                                        $subNilai = $get('sub_nilai');
                                        if($subNilai){
                                            $set('sub_nilai', null);
                                        }
                                    })
                                    ->required()
                                    ->reactive(),
                            ]),
                            Step::make('Sub Nilai')
                            ->schema([
                                Radio::make('sub_nilai')
                                    ->options(function($state, callable $get, callable $set){
                                        $nilai = $get('nilai');
                                        if($nilai){
                                            return Value::with('subValue')->find($nilai)->subValue->pluck('description', 'id');
                                        }
                                        return [];
                                    })
                                    ->required(),
                            ]),
                        ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('project')
            ->columns([
                Tables\Columns\TextColumn::make('project.name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
