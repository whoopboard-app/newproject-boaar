<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PublicUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'full_name',
        'magic_token',
        'magic_token_expires_at',
        'last_login_at',
        'last_ip',
        'device_fingerprint',
        'is_verified',
        'email_verified_at',
    ];

    protected $casts = [
        'magic_token_expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    protected $hidden = [
        'magic_token',
    ];

    /**
     * Get the votes for the public user.
     */
    public function votes()
    {
        return $this->hasMany(FeedbackVote::class);
    }

    /**
     * Get the feedbacks submitted by the public user.
     */
    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'public_user_id');
    }

    /**
     * Generate a magic link token.
     */
    public function generateMagicToken()
    {
        $this->magic_token = Str::random(64);
        $this->magic_token_expires_at = now()->addMinutes(15);
        $this->save();

        return $this->magic_token;
    }

    /**
     * Check if the magic token is valid.
     */
    public function isMagicTokenValid()
    {
        return $this->magic_token
            && $this->magic_token_expires_at
            && $this->magic_token_expires_at->isFuture();
    }

    /**
     * Mark as logged in.
     */
    public function markAsLoggedIn($ip = null)
    {
        $this->last_login_at = now();
        $this->last_ip = $ip;
        $this->magic_token = null;
        $this->magic_token_expires_at = null;
        $this->save();
    }

    /**
     * Check if user is verified.
     */
    public function isVerified()
    {
        return $this->is_verified;
    }

    /**
     * Mark email as verified.
     */
    public function markEmailAsVerified()
    {
        $this->is_verified = true;
        $this->email_verified_at = now();
        $this->save();
    }
}
