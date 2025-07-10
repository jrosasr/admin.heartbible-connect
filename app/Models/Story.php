<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'verses_count',
        'location',
        'difficulty',
    ];

    /**
     * The users who have learned this story.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_stories')
                    ->withPivot('learned_at')
                    ->withTimestamps();
    }

    /**
     * The series that this story belongs to.
     */
    public function series(): BelongsToMany // ¡Añade esta relación!
    {
        return $this->belongsToMany(Serie::class, 'story_series')->withTimestamps();
    }
}