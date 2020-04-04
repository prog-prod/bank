<?php

namespace App\Http\Controllers;

use App\HistoryTransaction;
use App\Http\Requests\AvatarRequest;
use Illuminate\Http\Request;

class HomeController extends MainController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('home',[
            'historyTransactions' => $request->user()->history(),
            'cards' => $request->user()->cards()->get()
        ]);
    }

    /**
     * @param AvatarRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAvatar(AvatarRequest $request){

        $request->validated();
        $avatarName = $request->user()->id.'_avatar'.time().'.'.$request->file('avatar')->getClientOriginalExtension();

        $request->file('avatar')->storeAs('avatars', $avatarName);

        if($request->user()->updateAvatar($avatarName))
        {
            return back()
                ->with('success','You have successfully upload image.');
        }

        return back()
            ->withErrors(['error','You have\'t uploaded image.']);
    }
}
