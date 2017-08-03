<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserGame extends Model
{
    protected $fillable = [
    	'appid',
    	'user_id',
    	'title',
    	'playtime'
    ];

    public function user() {
    	return $this->hasOne('App\User', 'id', 'user_id');
    }

    public static function byAppid($appid, $user_id = null) {
    	if(!$user_id) {
    		$user_id = Auth::user()->id;
    	}

    	return self::where(['appid' => $appid, 'user_id' => $user_id])->first();
    }
}
