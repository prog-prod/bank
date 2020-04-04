<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryTransaction extends Model
{

    protected $fillable = [
        'amount','card_id', 'receiver_card_id'
    ];

    /**
     * @param array $data
     * @return mixed
     */
    public static function putTransaction(array $data)
    {
        return HistoryTransaction::create($data);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function card()
    {
        return $this->hasOne('App\Card','id','card_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function receiverCard()
    {
        return $this->hasOne('App\Card','id','receiver_card_id');
    }

    /**
     * @return bool
     */
    public function isReceiver()
    {
        return $this->receiver_card_id === $this->card->id;
    }
}
