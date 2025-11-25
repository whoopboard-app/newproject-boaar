<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'feedback_id',
        'user_id',
        'public_user_id',
        'commenter_name',
        'commenter_email',
        'comment',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    /**
     * Get the feedback that owns the comment.
     */
    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    /**
     * Get the user who made the comment (if logged in).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the public user who made the comment (if logged in as public user).
     */
    public function publicUser()
    {
        return $this->belongsTo(PublicUser::class);
    }

    /**
     * Get the commenter name.
     */
    public function getCommenterNameAttribute($value)
    {
        if ($this->user) {
            return $this->user->name;
        }
        if ($this->publicUser) {
            return $this->publicUser->full_name;
        }
        return $value;
    }
}
