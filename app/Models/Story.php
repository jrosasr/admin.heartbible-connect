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
        // YA NO USES ->using(UserStory::class) aquí
        return $this->belongsToMany(User::class, 'user_stories')
                    ->withPivot('learned_at')
                    ->withTimestamps();
    }
}