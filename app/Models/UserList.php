<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserList extends Model
{
    protected $fillable = ['user_id', 'name', 'type'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_user_list');
    }

    public function episodes(): BelongsToMany
    {
        return $this->belongsToMany(Episode::class, 'episode_user_list');
    }
}