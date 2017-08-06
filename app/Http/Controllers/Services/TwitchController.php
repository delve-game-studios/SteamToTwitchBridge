<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use \TwitchApi as TwitchApi;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TwitchController extends Controller
{

    private $token;

    public function callback(Request $request) {
        $code = $request->input('code');
        $response = json_decode(json_encode(TwitchApi::getAccessObject($code)), true);
        TwitchApi::setToken($response['access_token']);
        $user = TwitchApi::authUser($response['access_token']);

        $service = \App\Service::where(['slug' => 'service-twitch'])->first();

        $userService = \App\UserService::where([
            'user_id' => Auth::user()->id,
            'service_id' => $service->id
        ])->first();

        if(!$userService) {
            $userService = new \App\UserService();
        }

        $userService->user_id = Auth::user()->id;
        $userService->service_id = $service->id;
        $response['code'] = $code;
        $userService->settings = [
            'settings' => [
                'username' => $user['name']
            ],
            'access' => $response
        ];

        $userService->save();

        return redirect()->route('users.profile');
    }

    public function auth()
    {
        if(!Auth::user() || !($service = Auth::user()->hasService('service-twitch'))) {
            return redirect(TwitchApi::getAuthenticationUrl());
        } else {
            return redirect()->route('users.profile')->with('info', 'Twitch account already linked.');
        }
    }

    public function getStreamData(\App\User $user = null) {
        $this->loadToken($user);

        $channel = TwitchApi::authChannel();
        $stream = TwitchApi::liveChannel($channel['_id']); // [stream] => NULL --- when offline otherwise full description of the stream

        return $stream;
    }

    public function setGameAndStatus($request, \App\User $user = null) {
        $this->loadToken($user);

        $game = $request['game'];
        if($game == 'Counter-Strike') {
            $game .= ' Online';
        }

        $data = [];
        $data['game'] = $game;
        if(!empty($request['status'])) {
            $data['status'] = $request['status'];
        }

        $channel = TwitchApi::authChannel();
        $update = TwitchApi::updateChannel($channel['_id'], $data);
        return $update;
    }

    public function loadToken(\App\User $user = null) {
        if(!$user) {
            $user = Auth::user();
        }

        if($service = $user->hasService('service-twitch')) {
            $userService = $service->userService($user)->first();
            $this->token = $userService->settings['access']['access_token'];
            TwitchApi::setToken($userService->settings['access']['access_token']);
        }
    }

    public function isUserStreaming(\App\User $user) {
        $streamData = $this->getStreamData($user);

        if(gettype($streamData) == gettype('{}')) $streamData = json_decode($streamData);

        $status = !!$streamData['stream'];

        return ['status' => $status];
    }
}
