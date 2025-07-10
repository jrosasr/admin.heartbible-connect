<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserStoryResource\Pages;
use App\Models\UserStory;
use App\Models\User;
use App\Models\Story;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserStoryResource extends Resource
{
    protected static ?string $model = UserStory::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Progreso de Usuario';
    protected static ?int $navigationSort = 1; // Orden dentro del grupo Progreso de Usuario

    protected static ?string $modelLabel = 'Historia Aprendida';
    protected static ?string $pluralModelLabel = 'Historias Aprendidas';
    protected static ?string $navigationLabel = 'Historias Aprendidas';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Usuario'),

                Forms\Components\Select::make('story_id')
                    ->relationship('story', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Historia Bíblica'),

                Forms\Components\DatePicker::make('learned_at')
                    ->required()
                    ->default(now())
                    ->label('Fecha de Aprendizaje'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Usuario'),

                Tables\Columns\TextColumn::make('story.title')
                    ->searchable()
                    ->sortable()
                    ->label('Historia'),

                Tables\Columns\TextColumn::make('learned_at')
                    ->date()
                    ->sortable()
                    ->label('Fecha Aprendida'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Registrado El'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->label('Filtrar por Usuario'),
                Tables\Filters\SelectFilter::make('story')
                    ->relationship('story', 'title')
                    ->searchable()
                    ->label('Filtrar por Historia'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Eliminar seleccionados'),
                ])->label('Acciones en masa'),
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
            'index' => Pages\ListUserStories::route('/'),
            'create' => Pages\CreateUserStory::route('/create'),
            'edit' => Pages\EditUserStory::route('/{record}/edit'),
        ];
    }
}