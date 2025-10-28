<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venue extends Model
{
    /** @use HasFactory<\Database\Factories\VenueFactory> */
    use HasFactory;

    protected $fillable = [
        'venue_name',
        'venue_max_capacity',
        'venue_address',
        'venue_status'
    ];

    public function events(): HasMany
    {
        return $this->hasMany(
            Event::class,
            'fk_venue_event',
            'id'
        );
    }
}
