<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomRental extends Model
{
     protected $fillable = [
        'room_id',
        'rental_type',
        'price'
    ]; 

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
