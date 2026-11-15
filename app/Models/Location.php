<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['id', 'name', 'type', 'dimension'];

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class);
    }
}
