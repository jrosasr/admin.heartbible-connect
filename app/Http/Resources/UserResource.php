<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'dni' => $this->dni,
            'email_verified_at' => $this->email_verified_at ? $this->email_verified_at->format('d M Y H:i:s') : null,
            'created_at' => $this->created_at->format('d M Y H:i:s'),
            'updated_at' => $this->updated_at->format('d M Y H:i:s'),
            'stories' => StoryResource::collection($this->whenLoaded('stories')),
            'achievements' => AchievementResource::collection($this->whenLoaded('achievements')),
        ];
    }
}