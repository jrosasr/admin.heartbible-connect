<?php

namespace App\Filament\Resources\UserStoryResource\Pages;

use App\Filament\Resources\UserStoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserStory extends ViewRecord
{
    protected static string $resource = UserStoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
