<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon; // Asegúrate de importar Carbon

class StoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'verses_count' => $this->verses_count,
            'location' => $this->location,
            'difficulty' => $this->difficulty,
            'learned_at' => $this->whenPivotLoaded('user_stories', function () {
                $learnedAt = $this->pivot->learned_at;

                // Si ya es un objeto DateTime/Carbon, formatea directamente
                if ($learnedAt instanceof \DateTimeInterface) {
                    return $learnedAt->format('d M Y H:i');
                }

                // Si es una cadena no vacía, intenta parsearla a Carbon
                if (is_string($learnedAt) && !empty($learnedAt)) {
                    try {
                        return Carbon::parse($learnedAt)->format('d M Y H:i');
                    } catch (\Exception $e) {
                        // Si falla el parseo, retorna null o maneja el error
                        return null;
                    }
                }
                // Si es null, una cadena vacía o cualquier otro tipo no esperado, retorna null
                return null;
            }),
            'created_at' => $this->created_at->format('d M Y H:i:s'),
            'updated_at' => $this->updated_at->format('d M Y H:i:s'),
        ];
    }
}