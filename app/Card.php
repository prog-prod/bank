<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Card extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'date',
        'cvv'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne('App\Account');
    }

    /**
     * @param int $money
     * @return int
     */
    public function replenish(int $money)
    {
        return $this->account()->update([
            'amount' => DB::raw('amount+'.$money)
        ]);
    }

    /**
     * @param int $money
     * @return int
     */
    public function withdraw (int $money)
    {
        return $this->account()->update([
            'amount' => DB::raw('amount-'.$money)
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historyTransactions ()
    {
        return $this->hasMany('App\HistoryTransaction','card_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historyReceiverTransactions ()
    {
        return $this->hasMany('App\HistoryTransaction','receiver_card_id','id');
    }

}
