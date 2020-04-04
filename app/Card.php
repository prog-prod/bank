<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Card extends Model
{

    protected $fillable = [
        'number',
        'date',
        'cvv'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function account()
    {
        return $this->hasOne('App\Account');
    }

    public function replenish(int $money)
    {
        return $this->account()->update([
            'amount' => DB::raw('amount+'.$money)
        ]);
    }
}
