<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): Array
    {
        return [
            'all_users' => Tab::make()->label('All Users'),
            'teachers' => Tab::make()->label('Teachers')
                        ->modifyQueryUsing(
                            fn (Builder $query) => $query->teacher()
                        ),
            'students' => Tab::make()->label('Students')
                        ->modifyQueryUsing(
                            fn (Builder $query) => $query->student()
                        ),
        ];
    }
}
