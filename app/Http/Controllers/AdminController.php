<?php

namespace App\Http\Controllers;

use App\HistoryTransaction;
use App\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users ()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function transactions()
    {
        $transactions = HistoryTransaction::paginate(10);
        return view('admin.transactions.index', compact('transactions'));
    }
}
