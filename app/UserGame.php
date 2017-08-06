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

    public static function by($attr = []) {
        return self::where($attr)->first();
    }

    public static function byAppid($appid) {
        return self::by(['appid' => $appid]);
    }
}
