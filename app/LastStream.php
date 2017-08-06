<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LastStream extends Model
{
    protected $fillable = [
    	'user_id', 'last_game', 'status'
    ];

    public static function isUpdated(User $user = null, $current_game = '') {
    	if(!$user) $user = Auth::user();

    	$lastUpdate = self::firstOrNew([
    		'user_id' => $user->id
		]);

		if($lastUpdate->last_game === $current_game) {
			if($lastUpdate->status === 0) {
				$lastUpdate->update(['status' => 1]);
				return true;
			}
			return false;
		}

		return true;
    }

    public static function updateMe($attr = []) {
    	$lastStream = self::where([
    		'user_id' => $attr['user_id']
		])->first();

    	return !!$lastStream ? $lastStream->update($attr) : false;
    }
}
