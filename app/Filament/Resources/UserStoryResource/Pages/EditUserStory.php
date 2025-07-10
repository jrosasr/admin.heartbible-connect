<?php

namespace App\Filament\Resources\UserStoryResource\Pages;

use App\Filament\Resources\UserStoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserStory extends EditRecord
{
    protected static string $resource = UserStoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
