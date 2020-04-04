<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{

    /**
     * ChangePasswordController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index ()
    {
        return view('change-password');
    }

    /**
     * @param Request $request
     */
    public function store (ChangePasswordRequest $request)
    {
        $request->user()->updatePassword($request->new_password);

        return back()->with('success','Password has been changed.');
    }
}
