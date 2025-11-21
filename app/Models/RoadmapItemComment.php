<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadmapItemComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'roadmap_item_id',
        'user_id',
        'comment',
    ];

    /**
     * Get the roadmap item that owns the comment.
     */
    public function roadmapItem()
    {
        return $this->belongsTo(RoadmapItem::class);
    }

    /**
     * Get the user that wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
