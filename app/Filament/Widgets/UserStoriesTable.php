<?php

namespace App\Filament\Widgets;

use App\Models\UserStory; // ¡Importa el modelo de la tabla pivote!
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class UserStoriesTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Historias Aprendidas por el Usuario';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $selectedUserId = $this->filters['user_id'] ?? null;

        return $table
            // La consulta ahora se basa en el modelo de la tabla pivote
            ->query(function () use ($selectedUserId) {
                if (!$selectedUserId) {
                    return UserStory::query()->whereRaw('FALSE'); // No mostrar nada si no hay usuario
                }

                return UserStory::query()
                    ->where('user_id', $selectedUserId) // Filtrar directamente por user_id
                    ->with('story') // Cargar la relación de la historia
                    ->orderByDesc('learned_at'); // Ordenar por la fecha en que se aprendió la historia
            })
            ->columns([
                Tables\Columns\TextColumn::make('story.title') // Acceder al título de la historia
                    ->searchable()
                    ->sortable()
                    ->label('Historia'),
                Tables\Columns\TextColumn::make('story.location') // Acceder a la ubicación
                    ->searchable()
                    ->label('Ubicación'),
                Tables\Columns\TextColumn::make('story.difficulty') // Acceder a la dificultad
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
                Tables\Columns\TextColumn::make('learned_at') // Acceder directamente al campo de la tabla pivote
                    ->dateTime()
                    ->sortable()
                    ->label('Fecha Aprendida'),
            ]);
    }
}