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
        $find = $this->cards()->find($cardId);

        if(!$find)
        {
            return false;
        }

        return $find->replenish($money);
    }

    /**
     * @param array $data
     * @return bool|mixed
     */
    public function createCard (array $data): Card
    {
        if(!Card::checkCard($data['number']))
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


    /**
     * @return mixed
     */
    public function getWholeAmount ()
    {
        return $this->cards()
            ->leftJoin('accounts',DB::raw('cards.id'),'=', DB::raw('accounts.card_id'))
            ->sum('amount');
    }

    /**
     * @param int $paginate
     * @return mixed
     */
    public function getHistory (int $paginate)
    {
        $cards = $this->cards()->pluck('id');
        return HistoryTransaction::whereIn('card_id',$cards)
            ->orWhereIn('receiver_card_id', $cards)
            ->latest()->paginate($paginate);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isMyCard (int $id)
    {
        return $this->cards()->get()->contains(function ($v) use ($id){

            return $v->id === $id;
        });
    }

    public function adminlte_image()
    {
        return '/storage/avatars/'.$this->avatar;
    }

    public function adminlte_desc()
    {
        return 'That\'s a nice guy';
    }
}
