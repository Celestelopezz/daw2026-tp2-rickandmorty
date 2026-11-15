<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Episode extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'name', 'air_date', 'episode', 'created'];

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_episode');
    }
}
