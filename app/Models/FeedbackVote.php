<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'feedback_id',
        'public_user_id',
        'voter_email',
        'voter_ip',
        'device_fingerprint',
        'voted_at',
    ];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    /**
     * Get the feedback that owns the vote.
     */
    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    /**
     * Get the public user that owns the vote.
     */
    public function publicUser()
    {
        return $this->belongsTo(PublicUser::class);
    }

    /**
     * Check if an IP has exceeded the rate limit.
     */
    public static function hasExceededRateLimit($ip, $limitPerHour)
    {
        $oneHourAgo = now()->subHour();
        $voteCount = static::where('voter_ip', $ip)
            ->where('voted_at', '>=', $oneHourAgo)
            ->count();

        return $voteCount >= $limitPerHour;
    }

    /**
     * Check if a user has already voted on a feedback item.
     */
    public static function hasVoted($feedbackId, $publicUserId = null, $email = null, $ip = null)
    {
        $query = static::where('feedback_id', $feedbackId);

        if ($publicUserId) {
            $query->where('public_user_id', $publicUserId);
        } elseif ($email) {
            $query->where('voter_email', $email);
        } elseif ($ip) {
            $query->where('voter_ip', $ip);
        }

        return $query->exists();
    }

    /**
     * Remove a vote.
     */
    public static function removeVote($feedbackId, $publicUserId = null, $email = null, $ip = null)
    {
        $query = static::where('feedback_id', $feedbackId);

        if ($publicUserId) {
            $query->where('public_user_id', $publicUserId);
        } elseif ($email) {
            $query->where('voter_email', $email);
        } elseif ($ip) {
            $query->where('voter_ip', $ip);
        }

        return $query->delete();
    }
}
