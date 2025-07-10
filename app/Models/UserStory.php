<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // ¡CAMBIA Pivot a Model!

class UserStory extends Model // ¡CAMBIA Pivot a Model!
{
    protected $table = 'user_stories';

    // Ahora 'id' es la clave primaria por defecto de Eloquent, no necesitas especificarla.
    // protected $primaryKey = 'id'; // Esto no es necesario si la columna se llama 'id'

    protected $fillable = [
        'user_id',
        'story_id',
        'learned_at',
    ];

    protected $casts = [
        'learned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}