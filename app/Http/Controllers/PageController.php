<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class PageController extends Controller
{
    public function dashboard(User $user)
    {
        $totalNetwork = count($user->getNetwork(auth()->user()->id));

        return view('pages.dashboard', compact('totalNetwork'));
    }
}
