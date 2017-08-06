<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;
use App\User;
use App\UserGame;
use App\Role;
use Illuminate\Support\Facades\Auth;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateGameForAllUsers() {
        $users = User::all();
        foreach($users as $user) {
            $data = app()->make('App\Http\Controllers\Services\TwitchController')->callAction('isUserStreaming', [$user]);
            var_dump($data);exit;
            if(!!$data['status']) {
                $steamData = app()->make('App\Http\Controllers\Services\SteamController')->callAction('getProfileData', [$user]);
                var_dump($steamData);exit;
            }
        }
    }

    public function serviceSequence(Request $request = null) {
        if(!!$request && $request->has('users')) {
            $usersInput = $request->input('users');
            
            if(preg_match('@,@', $usersInput)) {
                $usersInput = explode(',', $usersInput);
            }

            $users = User::find($usersInput);
        } else {
            $users = User::where([
                'role_id' => 4
            ])->get()->toArray();
        }

        foreach($users as $userArray) {
            $user = User::find($userArray['id']);
            if($this->checkStreamSequence($user) && $game = $this->checkSteamSequence($user)) {
                $this->updateStreamSequence($user, $game);

                if($user->isPremium()) {
                    $this->updateFacebookSequence($user, $game);
                }
            }

            \App\LastStream::updateMe([
                'user_id' => $user->id,
                'last_game' => $game
            ]);
        }
    }

    private function checkStreamSequence(User $user) {
        $TwitchController = app()->make('App\Http\Controllers\Services\TwitchController');
        $state = !!$TwitchController->callAction('getStreamData', ['user' => $user]);
        return $state;
    }

    private function checkSteamSequence(User $user) {
        $SteamController = app()->make('App\Http\Controllers\Services\SteamController');
        $steamData = $SteamController->callAction('getProfileData', ['user' => $user]);

        if(!empty($steamData['gameextrainfo'])) {
            if($userGame = UserGame::byAppid($steamData['gameid'])) { // returning Object || null
                return $userGame->title;
            } else {
                return $steamData['gameextrainfo']; // if null return object
            }
        }

        return false;
    }

    private function updateStreamSequence(User $user, $game) {
        $TwitchController = app()->make('App\Http\Controllers\Services\TwitchController');
        $TwitchController->callAction('setGameAndStatus', [['game' => $game], $user]);
    }

    private function updateFacebookSequence(User $user, $game) {
        $FacebookController = app()->make('App\Http\Controllers\Services\FacebookController');
        $FacebookController->callAction('postMessage', ['user' => $user, 'gamge' => $game, 'redirect' => false]);
    }
}
