<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; // Asegúrate de importar Carbon

class AchievementResource extends JsonResource
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
            'description' => $this->description,
            'image_url' => $this->image_path ? Storage::url($this->image_path) : null,
            'awarded_at' => $this->whenPivotLoaded('user_achievements', function () {
                $awardedAt = $this->pivot->awarded_at;

                // Si ya es un objeto DateTime/Carbon, formatea directamente
                if ($awardedAt instanceof \DateTimeInterface) {
                    return $awardedAt->format('d M Y H:i');
                }

                // Si es una cadena no vacía, intenta parsearla a Carbon
                if (is_string($awardedAt) && !empty($awardedAt)) {
                    try {
                        return Carbon::parse($awardedAt)->format('d M Y H:i');
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