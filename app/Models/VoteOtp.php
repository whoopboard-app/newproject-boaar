<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VoteOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp_code',
        'feedback_id',
        'team_id',
        'ip_address',
        'expires_at',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Generate a new OTP for email verification.
     */
    public static function generateFor($email, $feedbackId, $teamId, $ip)
    {
        // Delete any existing unverified OTPs for this email and feedback
        static::where('email', $email)
            ->where('feedback_id', $feedbackId)
            ->whereNull('verified_at')
            ->delete();

        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return static::create([
            'email' => $email,
            'otp_code' => $otpCode,
            'feedback_id' => $feedbackId,
            'team_id' => $teamId,
            'ip_address' => $ip,
            'expires_at' => now()->addMinutes(10),
        ]);
    }

    /**
     * Check if OTP is valid.
     */
    public function isValid()
    {
        return $this->expires_at->isFuture() && is_null($this->verified_at);
    }

    /**
     * Mark OTP as verified.
     */
    public function markAsVerified()
    {
        $this->verified_at = now();
        $this->save();
    }

    /**
     * Check if email has been verified for voting in a team.
     */
    public static function hasVerifiedEmail($email, $teamId)
    {
        return static::where('email', $email)
            ->where('team_id', $teamId)
            ->whereNotNull('verified_at')
            ->exists();
    }

    /**
     * Verify OTP code.
     */
    public static function verify($email, $feedbackId, $otpCode)
    {
        $otp = static::where('email', $email)
            ->where('feedback_id', $feedbackId)
            ->where('otp_code', $otpCode)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->first();

        if ($otp) {
            $otp->markAsVerified();
            return true;
        }

        return false;
    }
}
