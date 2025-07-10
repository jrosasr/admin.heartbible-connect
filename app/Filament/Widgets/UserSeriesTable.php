<?php

namespace App\Filament\Widgets;

use App\Models\Serie;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class UserSeriesTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Series con Historias Aprendidas';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $selectedUserId = $this->filters['user_id'] ?? null;

        return $table
            ->query(function () use ($selectedUserId) {
                if (!$selectedUserId) {
                    return Serie::query()->whereRaw('FALSE');
                }

                $user = User::find($selectedUserId);

                // Si el usuario no existe o no ha aprendido ninguna historia, no mostrar nada
                if (!$user || $user->stories->isEmpty()) {
                    return Serie::query()->whereRaw('FALSE');
                }

                // Obtenemos los IDs de las historias aprendidas por el usuario
                $learnedStoryIds = $user->stories->pluck('id')->toArray();

                // Carga las series que contienen alguna de las historias aprendidas por el usuario
                return Serie::query()
                    ->whereHas('stories', function (Builder $query) use ($learnedStoryIds) {
                        $query->whereIn('stories.id', $learnedStoryIds);
                    })
                    ->distinct(); // Asegura series únicas si una serie tiene varias historias aprendidas
            })
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Serie'),
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
                // Si quieres mostrar la fecha de creación de la serie:
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->label('Creada el'),
            ]);
    }
}