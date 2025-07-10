<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'dni', // Asumo que cambiaste 'cedula' a 'dni' como en 0001_01_01_000000_create_users_table.php
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the stories that the user has learned.
     */
    public function stories(): BelongsToMany
    {
        // YA NO USES ->using(UserStory::class) aquí
        return $this->belongsToMany(Story::class, 'user_stories')
                    ->withPivot('learned_at')
                    ->withTimestamps();
    }
}