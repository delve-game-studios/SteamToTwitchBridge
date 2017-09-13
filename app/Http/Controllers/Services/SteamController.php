<?php

namespace App\Http\Controllers\Services;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use \SteamApi as SteamApi;
use App\UserGame;
use App\User;
use Illuminate\Support\Facades\Session;

class SteamController extends Controller
{
    private $api;
    private $serviceSettings;
    private $user;
    private $service;
    private $slug;

    public function load(User $user = null) {
        $this->user = !!$user ? $user : Auth::user();
        $this->slug = 'service-steam';
        $this->service = \App\Service::bySlug($this->slug);
        $this->api = env('STEAM_API', '');
        $this->serviceSettings = \App\UserService::where([
            'service_id' => $this->service->id,
            'user_id' => $this->user->id
        ])->first()->settings;
    }

    public function callback(Request $request) {
    	$data = $request->all();
    	$openid_identity = preg_match('@[0-9]*$@', $data['openid_identity'], $matches);
    	if(!!$openid_identity) {
    		$data['steam_id'] = $matches[0];

    		if(!$userService = Auth::user()->hasService('service-steam')) {
    			$userService = new \App\UserService();
    		}

    		$service = \App\Service::bySlug('service-steam');

			$userService->user_id = Auth::user()->id;
			$userService->service_id = $service->id;
			$userService->settings = [
				'settings' => [],
				'access' => $data
			];

			$userService->save();

			return redirect()->route('users.profile')->with('success', 'Steam profile linked!');

    	}

    	return redirect()->route('users.profile')->with('error', 'Canceled!');
    }

    public function auth()
    {
    	if(!Auth::user()->hasService('service-steam')) {
			$oid = new \LightOpenID('https://vugrinchev.com/service/steam/callback');
			$oid->returnUrl = 'https://vugrinchev.com/service/steam/callback';
			$oid->identity = 'http://steamcommunity.com/openid/?l=english';
			
			return redirect($oid->authUrl());
		}

		return redirect()->route('users.profile')->with('info', 'Steam already linked.');
    }

    public function getProfileData(User $user = null) {
        $this->load($user);
        $client = new \GuzzleHttp\Client();

        $url = sprintf('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=%s&steamids=%s', $this->api, $this->serviceSettings['access']['steam_id']);
        $res = $client->get($url);

        if($res->getStatusCode() == 200) {
            $body = json_decode($res->getBody(), true);
            return $body['response']['players'][0];
        }

        return false;
    }

    public function updateUserGames() {
        $this->load();
        $client = new \GuzzleHttp\Client();

        $url = sprintf('http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=%s&steamid=%s&include_appinfo=1&include_played_free_games=1', $this->api, $this->serviceSettings['access']['steam_id']);
        $res = $client->get($url);

        if($res->getStatusCode() == 200) {
            $data = json_decode($res->getBody(), true);
            foreach($data['response']['games'] as $game) {
                if(!($userGame = UserGame::where('appid', $game['appid'])->first())) {
                    UserGame::create([
                        'appid' => $game['appid'],
                        'playtime' => $game['playtime_forever'],
                        'title' => $game['name'],
                        'user_id' => Auth::user()->id
                    ]);
                }
            }

            return ['status' => 'success', 'message' => 'All your games uploaded successfully!'];
        }

        return ['error' => 'Something gone wrong! Please Try later!'];
    }

    public function destroy() {
        $this->load();

        if(!$service = Auth::user()->hasService($this->slug)) {
            Session::flash('error', 'Steam has not been linked yet!');
            return [
                'status' => 'Error',
                'code' => 'danger',
                'message' => 'Steam has not been linked yet!'
            ];
        }

        $service->userService()->first()->delete();
        Session::flash('success', 'Steam link - Removed!');

        return [
            'status' => 'Success',
            'code' => 'success',
            'message' => 'Steam link - Removed!'
        ];
    }
}
