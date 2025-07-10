<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchievementResource\Pages;
use App\Models\Achievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy'; // Icono de trofeo
    protected static ?string $navigationGroup = 'Gestión de Logros'; // Nuevo grupo de navegación
    protected static ?int $navigationSort = 1;

    // Traducciones
    protected static ?string $modelLabel = 'Logro';
    protected static ?string $pluralModelLabel = 'Logros';
    protected static ?string $navigationLabel = 'Logros';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true) // Asegura que el título sea único
                    ->label('Título del Logro')
                    ->placeholder('Ej: Aprendiz de la Biblia'),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535)
                    ->label('Descripción del Logro')
                    ->placeholder('Ej: Este logro se otorga por aprender las primeras 10 historias.'),

                Forms\Components\FileUpload::make('image_path')
                    ->label('Imagen del Logro')
                    ->directory('achievements/images') // Directorio donde se guardarán las imágenes
                    ->visibility('public') // Hará que las imágenes sean accesibles públicamente
                    ->image() // Valida que el archivo sea una imagen
                    ->imageEditor() // Permite editar la imagen (recortar, rotar)
                    ->imageEditorViewportWidth('100') // Ancho del viewport para el editor
                    ->imageEditorViewportHeight('100') // Alto del viewport para el editor
                    ->imageEditorMode(2) // Permite recortar la imagen libremente o con proporción
                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp']) // Tipos de archivo aceptados
                    ->maxSize(2048) // Tamaño máximo en KB (2MB)
                    ->hint('La imagen debe ser cuadrada (ej. 100x100px) para una mejor visualización.')
                    ->storeFileNamesIn('original_filename') // Guarda el nombre original del archivo
                    ->columnSpanFull(), // Hace que ocupe todo el ancho de la columna
            ])->columns(1); // Un solo campo por fila para el formulario
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->square() // Muestra la imagen de forma cuadrada
                    ->width(50) // Ancho en la tabla
                    ->height(50) // Alto en la tabla
                    ->label('Imagen'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label('Título'),

                Tables\Columns\TextColumn::make('description')
                    ->limit(70) // Limita la descripción para la tabla
                    ->tooltip(fn (Achievement $record): string => $record->description) // Tooltip para la descripción completa
                    ->label('Descripción'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Fecha de Creación'),
            ])
            ->filters([
                //
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
            // Podrías añadir un Relation Manager para UserAchievement aquí si quisieras ver
            // qué usuarios tienen este logro directamente desde la vista del logro.
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }
}