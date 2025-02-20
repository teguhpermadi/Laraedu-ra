<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Resources\TeacherResource\RelationManagers;
use App\Filament\Resources\TeacherResource\RelationManagers\TeacherSubjectRelationManager;
use App\Filament\Resources\TeacherResource\Widgets\TeacherWidget;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Userable;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Database';

    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('gender')->options(['laki-laki'=>'Laki-laki', 'perempuan'=>'Perempuan'])->required(),
                // Select::make('active')->boolean()->default(true)->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('gender'),
                // TextColumn::make('userable.user.username')->label('Username'),
                IconColumn::make('active')->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Action::make('Userable')->action(function (Teacher $record){
                    $userable = Userable::where([
                        'userable_id' => $record->id,
                        'userable_type' => 'Teacher'
                    ])->count();

                    if($userable == 0){
                        $username = explode(' ', $record->name);
                        $user = User::create([
                                    'name' => $record->name,
                                    'username' => $username[0],
                                    'email' => Str::slug($record->name).'@teacher.com',
                                    'password' => Hash::make('password'),
                                ]);

                        Userable::create([
                            'user_id' => $user->id,
                            'userable_id' => $record->id,
                            'userable_type' => Teacher::class,
                        ]);

                        $user->assignRole('teacher');

                        Notification::make()->title('User berhasil dibuat')->success()->send();
                    } else {
                        Notification::make()->title('User sudah dibuat')->warning()->send();

                    }

                })
                    ->icon('heroicon-m-user-circle')
                    ->hidden(fn (Teacher $record) => $record->hasUserable()),
                    // ->visible(auth()->user()->hasPermissionTo('userable Teacher')),// Action akan tersembunyi jika guru memiliki Userable
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            TeacherSubjectRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'view' => Pages\ViewTeacher::route('/{record}'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }  
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
