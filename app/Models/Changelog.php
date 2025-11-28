<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    use BelongsToTeam;

    protected $fillable = [
        'team_id',
        'title',
        'cover_image',
        'short_description',
        'description',
        'tags',
        'author_name',
        'published_date',
        'status',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_date' => 'date',
    ];

    /**
     * Get all categories for this changelog (many-to-many)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'changelog_category')
                    ->withTimestamps();
    }
}
