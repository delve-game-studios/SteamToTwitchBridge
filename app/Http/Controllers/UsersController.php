<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UsersController extends Controller
{

    public function __construct() {
        $title = 'asd';
    }

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
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::user();
        $tempS = \App\Service::all()->toArray();
        $services = [];
        $slugs = array_keys($tempS);
        foreach($tempS as $key => $service) {            
            if($user->hasService($service['slug'])) {
                $tempS[$key]['connected'] = true;
                $userServiceSettings = $user->getService($service['slug'])->getSettings($user);
                $userAccessSettings  = $user->getService($service['slug'])->getAccessSettings($user);
                $tempS[$key]['settings'] = $userServiceSettings;
                $tempS[$key]['settings']['access'] = $userAccessSettings;
            } else {
                $tempS[$key]['connected'] = false;
            }

            if(!Auth::user()->isSubscriber()) {
                $tempS[$key]['hidden'] = in_array($tempS[$key]['id'], [4,5,'4','5']);
            }

            $services[$service['slug']] = $tempS[$key];
        }

        $services['service-facebook']['disabled'] = (empty($services['service-twitch']['connected']) || empty($services['service-steam']['connected']));

        return view('users.profile', compact('user', 'services'));
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

    public function loginAs($id) {
        if(Auth::user()->isAdmin()) {
            $user = User::find($id);
            Auth::login($user);
        }

        return redirect()->route('users.profile');
    }
}
