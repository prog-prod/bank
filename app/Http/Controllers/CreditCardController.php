<?php

namespace App\Http\Controllers;

use App\Card;
use App\Events\replenishAccount;
use App\Http\Requests\CardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CreditCardController extends Controller
{

    private $replenishMoney;

    public function __construct()
    {
        $this->replenishMoney = (int) config('replenish.money');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $creditCards = \Auth::user()->cards()->paginate(10);
        return view('credit-cards.index', compact('creditCards'));
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('credit-cards.create');
    }


    /**
     * @param CardRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CardRequest $request)
    {
        $data = collect($request->validated())->map(function ($name,$key) {

            if($key === 'cvv')
            {
                return Hash::make($name);
            }
            return $name;
        });

        $createCard = $request->user()->createCard($data->toArray());

        if($createCard)
        {
            $request->user()->replenishAccount($this->replenishMoney,$createCard->id);
            event(new replenishAccount($request->user()->email, 'Replenish your account', $this->replenishMoney));

            return back()->with('success', 'A Credit Card has been created');
        }

        return back()->withErrors( ['error' => 'The Card is not valid.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id, Request $request)
    {
        $request->user()->deleteCard($id);
        return redirect()->route('credit_cards.index')->with('success', 'A Credit Card has been deleted');
    }
}
