<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    protected $fillable = ['service_id', 'user_id', 'settings'];

    public function getSettingsAttribute($value) {
    	return json_decode($value, true);
    }

    public function setSettingsAttribute($value) {
    	if(gettype($value) !== gettype('')) {
    		$this->attributes['settings'] = json_encode($value);
    	} else {
    		$this->attributes['settings'] = $value;
    	}
    }
}
