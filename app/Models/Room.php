<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'properties_id',
        'room_number',
        'floor',
        'status',
        'room_type',
        'gender_target',
        'image'
    ]; 

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
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

