<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SiteAccessInvite extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'email',
        'verification_code',
        'code_expires_at',
        'verified_at',
        'access_expires_at',
    ];

    protected $casts = [
        'code_expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'access_expires_at' => 'datetime',
    ];

    /**
     * Get the team that owns the invite.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Generate a new verification code.
     */
    public function generateVerificationCode(): string
    {
        $code = strtoupper(Str::random(6));
        $this->verification_code = $code;
        $this->code_expires_at = now()->addMinutes(15);
        $this->save();

        return $code;
    }

    /**
     * Check if the verification code is valid.
     */
    public function isCodeValid(string $code): bool
    {
        return $this->verification_code === strtoupper($code)
            && $this->code_expires_at
            && $this->code_expires_at->isFuture();
    }

    /**
     * Mark the invite as verified.
     */
    public function markAsVerified(): void
    {
        $this->verified_at = now();
        $this->verification_code = null;
        $this->code_expires_at = null;
        $this->save();
    }

    /**
     * Check if the user has valid access.
     */
    public function hasValidAccess(): bool
    {
        if (!$this->verified_at) {
            return false;
        }

        // Check if access has expired
        if ($this->access_expires_at && $this->access_expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Scope to find by email and team.
     */
    public function scopeForEmailAndTeam($query, string $email, int $teamId)
    {
        return $query->where('email', strtolower($email))
            ->where('team_id', $teamId);
    }

    /**
     * Scope to find verified invites.
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Set email attribute to lowercase.
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
}
