<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'arrival_date',
        'departure_date',
        'number_people',
        'number_nights',
        'total_amount',
        'status'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    protected static function booted()
    {
        parent::booted();

        static::creating(function (Booking $booking) {
            if ($booking->arrival_date && $booking->number_nights){
                $departure_date = Carbon::parse($booking->arrival_date)->addDays($booking->number_nights);
                $booking->departure_date = $departure_date;
            }
        });
    }
}
