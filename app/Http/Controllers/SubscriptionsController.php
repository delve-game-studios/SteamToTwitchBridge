<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('subscription.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('subscription.view');
    }

    public function sendReminder(User $user) {}

    public function ppCallback(Request $request) {}

    public function buy(Request $request, User $user = null) {}
}
