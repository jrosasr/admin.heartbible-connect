<?php

namespace Database\Factories;

use App\Models\Story; // Importa tu modelo Story
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Story>
 */
class StoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Story::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $difficulty = $this->faker->randomElement(['low', 'medium', 'high', 'legend']);
        $title = $this->faker->unique()->sentence(rand(3, 7)); // Título de 3 a 7 palabras

        // Algunos lugares bíblicos para hacerlos más realistas
        $locations = [
            'Mateo 5:3-12', // Las Bienaventuranzas
            'Juan 3:16',    // Dios ama al mundo
            'Hechos 2:1-4',  // Pentecostés
            'Génesis 1:1',  // La Creación
            'Éxodo 14:21-31',// Cruce del Mar Rojo
            'Lucas 15:11-32',// El Hijo Pródigo
            'Mateo 26:26-29',// La Última Cena
            'Apocalipsis 21:1-4',// Nuevo Cielo y Nueva Tierra
            'Salmo 23',     // El Señor es mi Pastor
            'Proverbios 3:5-6', // Confía en el Señor
            'Romanos 8:28', // Todas las cosas ayudan a bien
        ];

        return [
            'title' => str_replace('.', '', $title), // Eliminar puntos del final del título
            'verses_count' => $this->faker->numberBetween(5, 50), // Entre 5 y 50 versículos
            'location' => $this->faker->randomElement($locations), // Ubicación bíblica
            'difficulty' => $difficulty,
        ];
    }
}