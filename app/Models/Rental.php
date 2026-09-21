<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    protected $fillable = [
        'tenants_id',
        'room_rentals_id',
        'start_date',
        'end_date',
        'rental_price',
        'status'
    ]; 

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenants_id');
    }

    public function roomRental()
    {
        return $this->belongsTo(RoomRental::class, 'room_rentals_id');
    }
}
 