<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Serie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'difficulty',
    ];

    /**
     * The stories that belong to the series.
     */
    public function stories(): BelongsToMany
    {
        return $this->belongsToMany(Story::class, 'story_series')->withTimestamps();
    }
}