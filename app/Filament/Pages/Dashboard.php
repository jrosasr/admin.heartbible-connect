<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Select; // Importa el componente Select
use Filament\Forms\Components\Section; // Importa el componente Section
use Filament\Forms\Form; // Importa la clase Form
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm; // Importa el trait
use App\Models\User; // Importa el modelo User

class Dashboard extends BaseDashboard
{
    // Usa el trait para añadir un formulario de filtros al dashboard
    use HasFiltersForm;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard'; // Título de tu Dashboard

    // Define el formulario de filtros que contendrá el selector de usuario
    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Filtros de Usuario') // Sección para organizar el filtro
                    ->schema([
                        Select::make('user_id')
                            ->label('Seleccionar Usuario')
                            ->placeholder('Selecciona un usuario para ver sus datos')
                            ->options(User::all()->pluck('name', 'id')) // Carga todos los usuarios
                            ->searchable() // Permite buscar usuarios
                            ->preload() // Carga las opciones por adelantado
                            ->live() // Hace que el dashboard se recargue cuando se cambia el selector
                            ->default(fn () => User::first()?->id), // Opcional: selecciona el primer usuario por defecto
                    ])
                    ->columns(1), // Una sola columna para el filtro
            ]);
    }
}