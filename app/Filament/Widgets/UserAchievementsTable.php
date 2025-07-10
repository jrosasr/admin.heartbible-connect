<?php

namespace App\Filament\Widgets;

use App\Models\UserAchievement; // ¡Importa el modelo de la tabla pivote!
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;

class UserAchievementsTable extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Logros del Usuario';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $selectedUserId = $this->filters['user_id'] ?? null;

        return $table
            // La consulta ahora se basa en el modelo de la tabla pivote
            ->query(function () use ($selectedUserId) {
                if (!$selectedUserId) {
                    return UserAchievement::query()->whereRaw('FALSE'); // No mostrar nada si no hay usuario
                }

                return UserAchievement::query()
                    ->where('user_id', $selectedUserId) // Filtrar directamente por user_id
                    ->with('achievement') // Cargar la relación del logro
                    ->orderByDesc('awarded_at'); // Ordenar por la fecha en que se obtuvo el logro
            })
            ->columns([
                Tables\Columns\ImageColumn::make('achievement.image_path') // Acceder a la imagen a través de la relación
                    ->square()
                    ->width(30)
                    ->height(30)
                    ->label('Imagen'),
                Tables\Columns\TextColumn::make('achievement.title') // Acceder al título del logro
                    ->searchable()
                    ->sortable()
                    ->label('Logro'),
                Tables\Columns\TextColumn::make('achievement.description') // Acceder a la descripción del logro
                    ->limit(50)
                    ->tooltip(fn (UserAchievement $record): string => $record->achievement->description)
                    ->label('Descripción'),
                Tables\Columns\TextColumn::make('awarded_at') // Acceder directamente al campo de la tabla pivote
                    ->dateTime()
                    ->sortable()
                    ->label('Fecha Obtenido'),
            ]);
    }
}