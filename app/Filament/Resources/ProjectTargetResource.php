<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectTargetResource\Pages;
use App\Filament\Resources\ProjectTargetResource\RelationManagers;
use App\Models\Dimention;
use App\Models\Element;
use App\Models\ProjectTarget;
use App\Models\Value;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectTargetResource extends Resource
{
    protected static ?string $model = ProjectTarget::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Project')
                    ->relationship('project')
                    ->schema([
                        TextInput::make('name')->disabled(),
                        Textarea::make('description')->disabled(),
                    ]),
                Repeater::make('target')
                    ->columnSpanFull()
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
                            ->reactive(),
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
                            ->reactive(),
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
                            })
                            ->reactive(),
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
                            ->reactive(),
                        Radio::make('sub_nilai')
                            ->options(function($state, callable $get, callable $set){
                                $nilai = $get('nilai');
                                if($nilai){
                                    return Value::with('subValue')->find($nilai)->subValue->pluck('description', 'id');
                                }
                                return [];
                            }),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('project.name'),
                TextColumn::make('grade.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectTargets::route('/'),
            'create' => Pages\CreateProjectTarget::route('/create'),
            'edit' => Pages\EditProjectTarget::route('/{record}/edit'),
        ];
    }    
}
