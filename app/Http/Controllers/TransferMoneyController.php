<?php

namespace App\Http\Controllers;

use App\Card;
use App\Http\Requests\TransferMoneyRequest;
use App\Jobs\TransferMoney;
use App\User;
use Illuminate\Http\Request;

class TransferMoneyController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index ()
    {
        return view('transfer-money', [
            'users' => User::all()
        ]);
    }

    /**
     * @param TransferMoneyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store (TransferMoneyRequest $request)
    {

        $receiver = User::find($request->user_to);
        $card_from = Card::find($request->card_from);
        $card_to = Card::find($request->card_to);

        try{
            TransferMoney::dispatch($card_from, $card_to, $request->amount);
        }
        catch (\Exception $e)
        {
            \DB::rollBack();
            echo $e->getMessage();
            return back()
                ->withErrors(['error' => $e->getMessage()]);
        }

        return back()
        ->with('success',"You have successfully transfer money to {$receiver->email}.");
    }

    /**
     * @param $cards
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCards ($cards)
    {
        return response()->json($cards->pluck('number', 'id'));
    }
}
