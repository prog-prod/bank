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
        'exp_date',
        'cvv'
    ];

    protected $casts = [
        'exp_date' => 'date:m/y',
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


    /** Check a card number of Luna algorithm
     * @param string $number
     * @return bool
     */
    public static function checkCard(string $number)
    {
        $result = [];
        # 1.
        for ($i = 0; $i < strlen($number); $i++)
        {
            $result[$i] = (int) $number[$i];
            if(($i+1) % 2 !== 0) {
                $result[$i] =  ($number[$i] * 2);
                #2
                if($result[$i] > 9){
                    $result[$i] -= 9;
                }
            }
        }

        #3
        if(array_sum($result) % 10 === 0){
            return true;
        }

        return false;
    }

    /**
     * @param int|null $number
     * @return int
     */
    public static function generateCardNumber (int $number = null)
    {

        if($number === null)
        {
            $number = rand(1111111111111111,9999999999999999);
        }

        $arrayNumber = array_map('intval', str_split($number));
        $arraySum = array_sum($arrayNumber);


        if($arraySum % 10 !== 0)
        {

            $difference = round($arraySum/10)*10 - $arraySum ;

            foreach($arrayNumber as &$value)
            {
                if(($value+$difference) >= 0 && ($value+$difference) < 10)
                {
                    $value += $difference;
                    break;
                }
            }

            self::generateCardNumber((int)implode('', $arrayNumber));

        }

        return $number;
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
