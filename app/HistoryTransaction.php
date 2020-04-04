<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryTransaction extends Model
{
    protected $fillable = [
        'amount','card_id', 'receiver_card_id'
    ];


    public static function putTransaction(array $data)
    {
        return HistoryTransaction::create($data);
    }

    public function card()
    {
        return $this->hasOne('App\Card','id','card_id');
    }

    public function receiverCard()
    {
        return $this->hasOne('App\Card','id','receiver_card_id');
    }

    public function isReceiver(){
        return $this->receiver_card_id === $this->card->id;
    }
}
