<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KnowledgeBoard extends Model
{
    protected $fillable = [
        'name',
        'short_description',
        'cover_page',
        'document_type',
        'visibility_type',
        'status',
        'board_owner_id',
        'has_public_url',
        'public_url',
    ];

    protected $casts = [
        'has_public_url' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($board) {
            if ($board->has_public_url && empty($board->public_url)) {
                $board->public_url = Str::random(32);
            }
        });
    }

    public function boardOwner()
    {
        return $this->belongsTo(User::class, 'board_owner_id');
    }
}
