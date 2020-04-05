<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferMoneyRequest;
use App\Jobs\TransferMoney;
use App\User;

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

        TransferMoney::dispatch(User::find($request->user_from), $receiver, $request->amount);

        return back()
        ->with('success',"You have successfully transfer money to {$receiver->email}.");
    }
}
