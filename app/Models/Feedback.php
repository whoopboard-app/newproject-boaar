<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory, BelongsToTeam;

    protected $table = 'feedbacks';

    protected $fillable = [
        'team_id',
        'idea',
        'feedback_category_id',
        'value_description',
        'roadmap_id',
        'name',
        'email',
        'login_access_enabled',
        'is_public',
        'tags',
        'persona_id',
        'source',
    ];

    protected $casts = [
        'tags' => 'array',
        'login_access_enabled' => 'boolean',
        'is_public' => 'boolean',
    ];

    /**
     * Get the feedback category.
     */
    public function category()
    {
        return $this->belongsTo(FeedbackCategory::class, 'feedback_category_id');
    }

    /**
     * Get the roadmap status.
     */
    public function roadmap()
    {
        return $this->belongsTo(Roadmap::class);
    }

    /**
     * Get the persona.
     */
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    /**
     * Get all comments for the feedback.
     */
    public function comments()
    {
        return $this->hasMany(FeedbackComment::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get public comments only.
     */
    public function publicComments()
    {
        return $this->hasMany(FeedbackComment::class)->where('is_internal', false)->orderBy('created_at', 'asc');
    }

    /**
     * Get internal comments only.
     */
    public function internalComments()
    {
        return $this->hasMany(FeedbackComment::class)->where('is_internal', true)->orderBy('created_at', 'asc');
    }
}
