<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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

    public function isAdmin()
    {
        if($this->role === 'admin')
        {
            return true;
        }

        return false;
    }

    public function cards()
    {
        return $this->hasMany('App\Card');
    }

    public function replenishAccount (int $money, int $cardId)
    {
        return $this->cards()->find($cardId)->replenish($money);
    }

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

    public function deleteCard (int $id)
    {
        return DB::transaction(function() use ($id){

            Account::where('card_id', $id)->delete();
            Card::destroy($id);
        });
    }

    public function updateAvatar (string $avatarName)
    {

        $this->avatar = $avatarName ;
        return $this->save();
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
