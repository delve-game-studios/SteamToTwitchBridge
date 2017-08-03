<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Service extends Model
{
    protected $fillable = [
    	'title', 'image'
    ];

    public function users() {
    	return $this->belongsToMany('App\User', 'user_services', 'service_id', 'id');
    }

    public function userService() {
        return $this->hasOne('\App\UserService', 'service_id')->where([
            'user_id' => Auth::user()->id
        ]);
    }

    public static function bySlug($slug) {
        return static::where(['slug' => $slug])->first();
    }

    /**
    *   @param \App\User $user
    *   @return Array
    */
    public function getSettings(\App\User $user = null) {
        $user = !!$user ? $user : Auth::user();

        $userService = $this->userService()->first();

        return !$userService == null ? $userService->settings : [];
    }


    // /**
    // *   @param \Array $settings
    // *   @param \App\User $user
    // */
    // public function setSettings(\Array $settings, \App\User $user = null) {
    //     $user = !!$user ? $user : Auth::user();

    //     $userService = \App\UserService::where([
    //         'user_id' => $user->id,
    //         'service_id' => $this->id
    //     ])->first();

    //     $userService->settings = $settings;
    //     $userService->save();
    // }
}
