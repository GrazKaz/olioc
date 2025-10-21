<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class County extends Model
{
    /** @use HasFactory<\Database\Factories\CountyFactory> */
    use HasFactory;

    protected $guarded = [];

    public function communes(): hasMany
    {
        return $this->hasMany(Commune::class);
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
