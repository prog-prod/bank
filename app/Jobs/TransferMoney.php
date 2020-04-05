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

    private $cardFrom;
    private $cardTo;
    private $amount;

    /**
     * Create a new job instance.
     *
     * @param Card $cardFrom
     * @param Card $cardTo
     * @param int $amount
     */
    public function __construct(Card $cardFrom,Card $cardTo, int $amount)
    {
        $this->cardFrom = $cardFrom;
        $this->cardTo = $cardTo;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        #1 transfer money
        DB::beginTransaction();

        # replenish receiver card;
        $this->cardTo->replenish($this->amount);

        # check cards if amount >= money

        if($this->cardFrom->account->amount < $this->amount)
        {
            throw new \Exception('There is not enough money in the account to make a transfer');
        }

        # withdraw cash
        $this->cardFrom->withdraw($this->amount);

        $this->writeToHistory();
        DB::commit();
    }


    /**
     *
     */
    private function writeToHistory (): void
    {
        $data = [
            'card_id' => $this->cardFrom->id,
            'receiver_card_id' => $this->cardTo->id,
            'amount' => $this->amount
        ];
        HistoryTransaction::putTransaction($data);
    }
}
