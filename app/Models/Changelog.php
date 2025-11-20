<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;

class Changelog extends Model
{
    use BelongsToUser;

    protected $fillable = [
        'user_id',
        'title',
        'cover_image',
        'short_description',
        'description',
        'category_id',
        'tags',
        'author_name',
        'published_date',
        'status',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
