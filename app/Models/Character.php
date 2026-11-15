<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Character extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'name', 'status', 'species', 'type',
        'gender', 'image', 'origin', 'location_id',
    ];

    public function location(): BelongsTo // muchos a uno 
    {
        return $this->belongsTo(Location::class);
    }

    public function episodes(): BelongsToMany
    {
        return $this->belongsToMany(Episode::class, 'character_episode');
    }
}