<?php

namespace App\Jobs;

use App\Card;
use App\HistoryTransaction;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class TransferMoney implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $receiver;
    private $amount;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param User $receiver
     * @param int $amount
     */
    public function __construct(User $user,User $receiver, int $amount)
    {
        $this->user = $user;
        $this->receiver = $receiver;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->transferMoney();
    }

    private function transferMoney ()
    {
        #1 get random user card
        $receiverCard = $this->receiver->cards()->get()->random();

        #2 transfer money
        DB::beginTransaction();

        try
        {
            # replenish receiver card;
            $receiverCard->replenish($this->amount);

            #get my cards where amount >= receive money
            $myCards = $this->user->cards()->whereHas('account', function ($q) {
                $q->where('amount','>', $this->amount-1);
            })->get();

            if($myCards->isEmpty())
            {
                throw new \Exception('There is not enough money in the account to make a transfer');
            }

            $myCard = $myCards->random();

            # withdraw cash
            $myCard->withdraw($this->amount);

            $this->writeToHistory($myCard);
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
     */
    private function writeToHistory (Card $myCard): void
    {
        $data = [
            'card_id' => $myCard->id,
            'receiver_card_id' => $this->receiver->id,
            'amount' => $this->amount
        ];
        HistoryTransaction::putTransaction($data);
    }
}
