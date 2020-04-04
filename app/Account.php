<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'amount',
        'card_id'
    ];


    public function card()
    {
        return $this->belongsTo('App\Card');
    }
}
