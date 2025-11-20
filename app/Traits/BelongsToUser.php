<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait BelongsToUser
{
    /**
     * Boot the trait.
     */
    protected static function bootBelongsToUser()
    {
        // Automatically add user_id when creating
        static::creating(function (Model $model) {
            if (Auth::check() && !$model->user_id) {
                $model->user_id = Auth::id();
            }
        });

        // Automatically scope all queries to current user
        static::addGlobalScope('user', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where($builder->getQuery()->from . '.user_id', Auth::id());
            }
        });
    }

    /**
     * Get the user that owns the model.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
