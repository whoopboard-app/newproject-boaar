<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'enable_login_for_voting',
        'enable_anonymous_voting',
        'enable_email_based_voting',
        'enable_internal_voting',
        'ip_rate_limit_per_hour',
        'require_email_otp_first_vote',
        'enable_device_fingerprinting',
        'enable_captcha',
    ];

    protected $casts = [
        'enable_login_for_voting' => 'boolean',
        'enable_anonymous_voting' => 'boolean',
        'enable_email_based_voting' => 'boolean',
        'enable_internal_voting' => 'boolean',
        'require_email_otp_first_vote' => 'boolean',
        'enable_device_fingerprinting' => 'boolean',
        'enable_captcha' => 'boolean',
        'ip_rate_limit_per_hour' => 'integer',
    ];

    /**
     * Get the team that owns the feedback settings.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get or create feedback settings for a team.
     */
    public static function forTeam($teamId)
    {
        return static::firstOrCreate(
            ['team_id' => $teamId],
            [
                'enable_login_for_voting' => false,
                'enable_anonymous_voting' => true,
                'enable_email_based_voting' => false,
                'enable_internal_voting' => false,
                'ip_rate_limit_per_hour' => 10,
                'require_email_otp_first_vote' => false,
                'enable_device_fingerprinting' => false,
                'enable_captcha' => false,
            ]
        );
    }
}
