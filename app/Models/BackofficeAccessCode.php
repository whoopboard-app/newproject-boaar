<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BackofficeAccessCode extends Model
{
    protected $fillable = [
        'email',
        'code',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Generate a new access code for the given email.
     */
    public static function generateFor(string $email): self
    {
        // Invalidate any existing unused codes for this email
        self::where('email', $email)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        return self::create([
            'email' => $email,
            'code' => strtoupper(Str::random(6)),
            'expires_at' => now()->addMinutes(30),
        ]);
    }

    /**
     * Check if the code is valid (not expired and not used).
     */
    public function isValid(): bool
    {
        return $this->used_at === null && $this->expires_at->isFuture();
    }

    /**
     * Mark the code as used.
     */
    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    /**
     * Find a valid code for the given email and code.
     */
    public static function findValidCode(string $email, string $code): ?self
    {
        return self::where('email', $email)
            ->where('code', strtoupper($code))
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();
    }
}
