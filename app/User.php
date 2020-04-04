<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable  implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return bool
     */
    public function isAdmin()
    {
        if($this->role === 'admin')
        {
            return true;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    /**
     * @param int $money
     * @param int $cardId
     * @return mixed
     */
    public function replenishAccount (int $money, int $cardId)
    {
        return $this->cards()->find($cardId)->replenish($money);
    }

    /**
     * @param string $number
     * @return bool
     */
    public function checkCard(string $number)
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
    public function generateCardNumber (int $number = null)
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

            $this->generateCardNumber((int)implode('', $arrayNumber));

        }

        return $number;
    }

    /**
     * @param array $data
     * @return bool|mixed
     */
    public function createCard (array $data)
    {
        if(!$this->checkCard($data['number']))
        {
            return false;
        }

        return DB::transaction(function() use ($data){

            $createCard = $this->cards()->create($data);

            Account::create([
                'card_id' => $createCard->id,
            ]);

            return $createCard;
        });

    }

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteCard (int $id)
    {
        return DB::transaction(function() use ($id){

            Account::where('card_id', $id)->delete();
            Card::destroy($id);
        });
    }

    /**
     * @param string $avatarName
     * @return bool
     */
    public function updateAvatar (string $avatarName)
    {

        $this->avatar = $avatarName ;
        return $this->save();
    }

    /**
     * @param $newPassword
     * @return bool
     */
    public function updatePassword ($newPassword)
    {
        $this->password = Hash::make($newPassword);
        return $this->save();
    }

    # Transfer money to the user

    /**
     * @param User $receiver
     * @param int $amount
     */
    public function transferMoney (User $receiver, int $amount)
    {
        #1 get random user card
        $receiverCard = $receiver->cards()->get()->random();

        #2 transfer money
        DB::beginTransaction();

        try
        {
            # replenish receiver card;
            $receiverCard->replenish($amount);

            #get my cards where amount >= receive money
            $myCards = $this->cards()->whereHas('account', function ($q) use ($amount) {
                $q->where('amount','>',$amount-1);
            })->get();

            if($myCards->isEmpty())
            {
                throw new \Exception('There is not enough money in the account to make a transfer');
            }

            $myCard = $myCards->random();

            # withdraw cash
            $myCard->withdraw($amount);

            $this->writeToHistory($myCard, $receiverCard, $amount);
            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            echo $e->getMessage();
        }
    }

    /**
     * @param Card $myCard
     * @param Card $receiverCard
     * @param int $amount
     */
    public function writeToHistory (Card $myCard, Card $receiverCard, int $amount): void
    {
        $data = [
            'card_id' => $myCard->id,
            'receiver_card_id' => $receiverCard->id,
            'amount' => $amount
        ];
        HistoryTransaction::putTransaction($data);
    }

    /**
     * @return mixed
     */
    public function getWholeAmount ()
    {
        return $this->cards()
            ->leftJoin('accounts',DB::raw('cards.id'),'=', DB::raw('accounts.card_id'))
            ->sum('amount');
    }

    public function history ()
    {
        $cards = $this->cards()->pluck('id');
        $result = HistoryTransaction::whereIn('card_id',$cards)
            ->orWhereIn('receiver_card_id', $cards)
//            ->with('card')
//            ->whereHas('card', function($q) use ($cards){
//                $q->whereIn('id',$cards);
//            })
            ->latest()->get();
//            ->toArray();

        return $result;
    }

    public function isMyCard (int $id)
    {
        return $this->cards()->get()->contains(function ($v) use ($id){

            return $v->id === $id;
        });
    }
    public static function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public static function adminlte_desc()
    {
        return 'That\'s a nice guy';
    }

}
