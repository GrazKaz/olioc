<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Commune extends Model
{
    /** @use HasFactory<\Database\Factories\CommuneFactory> */
    use HasFactory;

    protected $guarded = [];

    public function county(): belongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function users(): belongsTo
    {
        return $this->hasMany(User::class);
    }

    public function cards(): belongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_county_commune');
    }
}
