<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Story;
use App\Models\Achievement; // Importa el modelo Achievement
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crea un usuario de prueba si no existe
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'dni' => '1234567890', // Asegúrate de que este campo exista y sea único si es requerido
                'password' => bcrypt('password'), // Contraseña por defecto
            ]);
        }

        // Crea algunas historias de ejemplo (puedes crear más para probar los logros)
        if (Story::count() < 25) { // Asegúrate de tener suficientes historias para probar los logros
            Story::factory(25)->create(); // Crea 25 historias con datos ficticios
        }


        // Crea los logros si no existen
        $achievementsToCreate = [
            [
                'title' => 'Biblia Caminante',
                'description' => 'Otorgado por aprender más de 7 historias bíblicas.',
                'image_path' => null, // Puedes dejarlo nulo o poner una ruta de imagen por defecto
            ],
            [
                'title' => 'Nivel 1',
                'description' => 'Otorgado por aprender más de 7 historias bíblicas.',
                'image_path' => null,
            ],
            [
                'title' => 'Nivel 2',
                'description' => 'Otorgado por aprender más de 12 historias bíblicas.',
                'image_path' => null,
            ],
            [
                'title' => 'Nivel 3',
                'description' => 'Otorgado por aprender más de 22 historias bíblicas.',
                'image_path' => null,
            ],
            // Puedes añadir más logros aquí en el futuro
        ];

        foreach ($achievementsToCreate as $achievementData) {
            Achievement::firstOrCreate(
                ['title' => $achievementData['title']], // Busca por título
                $achievementData                          // Crea con estos datos si no existe
            );
        }

        // Opcional: Asignar algunas historias a un usuario de prueba para facilitar las pruebas
        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser && $testUser->stories()->count() == 0) {
            $stories = Story::inRandomOrder()->limit(10)->get(); // Asigna 10 historias al azar
            foreach ($stories as $story) {
                $testUser->stories()->attach($story->id, ['learned_at' => now()]);
            }
        }
    }
}