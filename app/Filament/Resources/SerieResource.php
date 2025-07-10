<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SerieResource\Pages;
use App\Models\Serie;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Story; // Asegúrate de que esta línea esté presente

class SerieResource extends Resource
{
    protected static ?string $model = Serie::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationGroup = 'Gestión Bíblica';
    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Serie';
    protected static ?string $pluralModelLabel = 'Series';
    protected static ?string $navigationLabel = 'Series';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->label('Título de la Serie')
                    ->placeholder('Ej: Todo Poderoso'),

                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->nullable()
                    ->label('Descripción'),

                Forms\Components\Select::make('difficulty')
                    ->options([
                        'low' => 'Baja',
                        'medium' => 'Media',
                        'high' => 'Alta',
                        'legend' => 'Leyenda',
                    ])
                    ->required()
                    ->label('Dificultad'),

                // Campo para seleccionar historias con botón de creación
                Forms\Components\Select::make('stories')
                    ->multiple()
                    ->relationship(
                        'stories',
                        'title',
                        fn (Builder $query) => $query->orderBy('title')
                    )
                    ->preload()
                    ->searchable()
                    ->getOptionLabelUsing(fn (Story $record): string => $record->title . ' (' . $record->location . ')')
                    ->label('Historias Relacionadas')
                    ->placeholder('Selecciona una o varias historias')
                    ->createOptionForm([ // Puedes personalizar el formulario del modal aquí
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->label('Título de la Historia')
                            ->placeholder('Ej: La Parábola del Hijo Pródigo'),
                        Forms\Components\TextInput::make('verses_count')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->label('Cantidad de Versículos')
                            ->placeholder('Ej: 20'),
                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255)
                            ->label('Ubicación en la Biblia')
                            ->placeholder('Ej: Lucas 15:11-32'),
                        Forms\Components\Select::make('difficulty')
                            ->options([
                                'low' => 'Baja',
                                'medium' => 'Media',
                                'high' => 'Alta',
                                'legend' => 'Leyenda',
                            ])
                            ->required()
                            ->label('Dificultad'),
                    ]),
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

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn (Serie $record): string => $record->description ?: '')
                    ->label('Descripción'),

                Tables\Columns\TextColumn::make('difficulty')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'Baja',
                        'medium' => 'Media',
                        'high' => 'Alta',
                        'legend' => 'Leyenda',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'success',
                        'medium' => 'warning',
                        'high' => 'danger',
                        'legend' => 'info',
                        default => 'gray',
                    })
                    ->sortable()
                    ->label('Dificultad'),

                Tables\Columns\TextColumn::make('stories.title')
                    ->listWithLineBreaks()
                    ->label('Historias Relacionadas'),

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
                        'legend' => 'Leyenda',
                    ])
                    ->label('Filtrar por Dificultad'),
                Tables\Filters\SelectFilter::make('stories')
                    ->relationship('stories', 'title')
                    ->label('Filtrar por Historia'),
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
            'index' => Pages\ListSeries::route('/'),
            'create' => Pages\CreateSerie::route('/create'),
            'edit' => Pages\EditSerie::route('/{record}/edit'),
        ];
    }
}