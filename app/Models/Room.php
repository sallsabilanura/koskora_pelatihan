<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'facilities_id',
        'properties_id',
        'room_number',
        'floor',
        'status'
    ]; 

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facilities_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'properties_id');
    }

    public function roomRentals()
    {
        return $this->hasMany(RoomRental::class, 'room_id');
    }
}

