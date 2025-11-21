<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadmapItem extends Model
{
    use HasFactory, BelongsToTeam;

    protected $fillable = [
        'team_id',
        'feedback_id',
        'idea',
        'notes',
        'tags',
        'external_pm_tool_id',
        'roadmap_status_id',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    /**
     * Get the feedback that this roadmap item is linked to.
     */
    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    /**
     * Get the roadmap status (from roadmap workflow).
     */
    public function roadmapStatus()
    {
        return $this->belongsTo(Roadmap::class, 'roadmap_status_id');
    }

    /**
     * Get all comments for this roadmap item.
     */
    public function comments()
    {
        return $this->hasMany(RoadmapItemComment::class)->orderBy('created_at', 'desc');
    }
}
