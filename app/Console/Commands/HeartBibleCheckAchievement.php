<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log; // Para registrar mensajes en los logs

class HeartBibleCheckAchievement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'heart-bible:check-achievements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica las historias aprendidas por cada usuario y asigna los logros correspondientes.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando la verificación y asignación de logros...');
        Log::info('Comando heart-bible:check-achievements iniciado.');

        // Obtener todos los logros que vamos a verificar
        $bibliaCaminante = Achievement::where('title', 'Biblia Caminante')->first();
        $nivel1 = Achievement::where('title', 'Nivel 1')->first();
        $nivel2 = Achievement::where('title', 'Nivel 2')->first();
        $nivel3 = Achievement::where('title', 'Nivel 3')->first();

        // Verificar que los logros existen
        if (!$bibliaCaminante || !$nivel1 || !$nivel2 || !$nivel3) {
            $this->error('Error: No se encontraron todos los logros necesarios. Asegúrate de haber ejecutado el seeder.');
            Log::error('Comando heart-bible:check-achievements fallido: Faltan logros en la base de datos.');
            return Command::FAILURE;
        }

        // Obtener todos los usuarios
        $users = User::all();

        $this->withProgressBar($users, function ($user) use ($bibliaCaminante, $nivel1, $nivel2, $nivel3) {
            $learnedStoriesCount = $user->stories()->count();

            $this->comment("Procesando usuario: {$user->name} (ID: {$user->id}) - Historias aprendidas: {$learnedStoriesCount}");
            Log::info("Procesando usuario: {$user->name} (ID: {$user->id}) - Historias aprendidas: {$learnedStoriesCount}");

            $achievementsToAward = [];
            $awardedAchievementTitles = []; // Para mensajes más claros

            // Criterios de logros
            if ($learnedStoriesCount > 7) {
                $achievementsToAward[] = $bibliaCaminante->id;
                $achievementsToAward[] = $nivel1->id;
                $awardedAchievementTitles[] = $bibliaCaminante->title;
                $awardedAchievementTitles[] = $nivel1->title;
            }

            if ($learnedStoriesCount > 12) {
                $achievementsToAward[] = $nivel2->id;
                $awardedAchievementTitles[] = $nivel2->title;
            }

            if ($learnedStoriesCount > 22) {
                $achievementsToAward[] = $nivel3->id;
                $awardedAchievementTitles[] = $nivel3->title;
            }

            // Asignar los logros al usuario
            if (!empty($achievementsToAward)) {
                // syncWithoutDetaching() agrega los nuevos logros sin eliminar los existentes
                // El array asociativo para withPivot permite establecer la fecha de otorgamiento
                $user->achievements()->syncWithoutDetaching(
                    collect($achievementsToAward)->mapWithKeys(function ($id) {
                        return [$id => ['awarded_at' => now()]];
                    })->all()
                );
                $this->info("  -> Logros asignados a {$user->name}: " . implode(', ', $awardedAchievementTitles));
                Log::info("  Logros asignados a {$user->name}: " . implode(', ', $awardedAchievementTitles));
            } else {
                $this->comment("  -> No se asignaron nuevos logros a {$user->name}.");
                Log::info("  No se asignaron nuevos logros a {$user->name}.");
            }
        });

        $this->info('Verificación y asignación de logros completada.');
        Log::info('Comando heart-bible:check-achievements completado.');

        return Command::SUCCESS;
    }
}