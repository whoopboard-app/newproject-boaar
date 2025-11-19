<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'source',
        'status',
        'subscribe_date',
        'verify_date',
        'verification_token',
    ];

    protected $casts = [
        'subscribe_date' => 'datetime',
        'verify_date' => 'datetime',
    ];

    /**
     * Relationship: Subscriber belongs to many User Segments
     */
    public function segments()
    {
        return $this->belongsToMany(UserSegment::class, 'subscriber_user_segment');
    }

    /**
     * Generate a unique verification token
     */
    public function generateVerificationToken()
    {
        $this->verification_token = Str::random(64);
        $this->save();
        return $this->verification_token;
    }

    /**
     * Mark subscriber as verified
     */
    public function markAsVerified()
    {
        $this->status = 'subscribed';
        $this->verify_date = now();
        $this->verification_token = null;
        $this->save();
    }

    /**
     * Mark subscriber as unsubscribed
     */
    public function unsubscribe()
    {
        $this->status = 'unsubscribed';
        $this->save();
    }

    /**
     * Check if subscriber is verified
     */
    public function isVerified()
    {
        return $this->status === 'subscribed';
    }

    /**
     * Check if subscriber is pending verification
     */
    public function isPendingVerification()
    {
        return $this->status === 'pending_verify';
    }

    /**
     * Scope to get only subscribed members
     */
    public function scopeSubscribed($query)
    {
        return $query->where('status', 'subscribed');
    }

    /**
     * Scope to get pending verification subscribers
     */
    public function scopePendingVerification($query)
    {
        return $query->where('status', 'pending_verify');
    }

    /**
     * Scope to get unsubscribed members
     */
    public function scopeUnsubscribed($query)
    {
        return $query->where('status', 'unsubscribed');
    }
}
