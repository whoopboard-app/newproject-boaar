<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'question_text',
        'rating_type',
        'apply_to_changelog',
        'apply_to_knowledge_board',
    ];

    protected $casts = [
        'apply_to_changelog' => 'boolean',
        'apply_to_knowledge_board' => 'boolean',
    ];

    /**
     * Rating type options
     */
    const RATING_TYPES = [
        'yes_no' => 'Yes / No',
        'emoji' => 'Emoji Rating',
        'star' => 'Star Rating',
        'numeric' => 'Numeric Count Rating',
        'comment_only' => 'Comment Only',
    ];

    /**
     * Get the team that owns the rating settings.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get or create rating settings for a team.
     */
    public static function forTeam($teamId)
    {
        return static::firstOrCreate(
            ['team_id' => $teamId],
            [
                'question_text' => 'Did this answer your question?',
                'rating_type' => 'yes_no',
                'apply_to_changelog' => true,
                'apply_to_knowledge_board' => true,
            ]
        );
    }

    /**
     * Get the rating type label
     */
    public function getRatingTypeLabelAttribute()
    {
        return self::RATING_TYPES[$this->rating_type] ?? $this->rating_type;
    }
}
