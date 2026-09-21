<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
  protected $fillable = [
        'rentals_id',
        'payment_date',
        'payment_period',
        'amount',
        'payment_method',
        'status'
    ]; 

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rentals_id');
    }
}
