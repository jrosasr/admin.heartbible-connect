<?php

namespace App\Filament\Resources\UserStoryResource\Pages;

use App\Filament\Resources\UserStoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserStory extends CreateRecord
{
    protected static string $resource = UserStoryResource::class;
}
