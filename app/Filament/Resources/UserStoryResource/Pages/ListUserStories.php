<?php

namespace App\Filament\Resources\UserStoryResource\Pages;

use App\Filament\Resources\UserStoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserStories extends ListRecords
{
    protected static string $resource = UserStoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
