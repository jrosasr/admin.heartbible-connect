<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoryResource\Pages;
use App\Models\Story;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StoryResource extends Resource
{
    protected static ?string $model = Story::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Gestión Bíblica';
    protected static ?int $navigationSort = 1; // Orden dentro de Gestión Bíblica

    protected static ?string $modelLabel = 'Historia';
    protected static ?string $pluralModelLabel = 'Historias';
    protected static ?string $navigationLabel = 'Historias';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Título de la Historia')
                    ->placeholder('Ej: El Buen Samaritano'),

                Forms\Components\TextInput::make('verses_count')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->label('Cantidad de Versículos')
                    ->placeholder('Ej: 15'),

                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(255)
                    ->label('Ubicación en la Biblia')
                    ->placeholder('Ej: Lucas 10:25-37'),

                Forms\Components\Select::make('difficulty')
                    ->options([
                        'low' => 'Baja',
                        'medium' => 'Media',
                        'high' => 'Alta',
                        'legend' => 'Legendaria'
                    ])
                    ->required()
                    ->label('Dificultad'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Título'),

                Tables\Columns\TextColumn::make('verses_count')
                    ->numeric()
                    ->sortable()
                    ->label('Versículos'),

                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable()
                    ->label('Ubicación'),

                Tables\Columns\TextColumn::make('difficulty')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        'legend' => 'primary',
                    })
                    ->label('Dificultad'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Fecha de Creación'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('difficulty')
                    ->options([
                        'low' => 'Baja',
                        'medium' => 'Media',
                        'high' => 'Alta',
                        'legend' => 'Legendaria',
                    ])
                    ->label('Filtrar por Dificultad'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Eliminar seleccionadas'),
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
            'index' => Pages\ListStories::route('/'),
            'create' => Pages\CreateStory::route('/create'),
            'edit' => Pages\EditStory::route('/{record}/edit'),
        ];
    }
}