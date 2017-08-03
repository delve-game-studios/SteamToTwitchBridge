<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Role extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'level'
    ];

    public function users() {
    	return $this->hasMany('App\User', 'role_id', 'id')->get();
    }

    public static function admin() {
    	return static::where([
    		'level' => static::max('level')
		])->first();
    }

    public function getTitleAttribute() {
        if($this->attributes['default_expiration'] !== 'Never') {
            return sprintf('%s* Expires: %s', $this->attributes['title'], Auth::user()->role_expire);
        }

        return $this->attributes['title'];
    }

    public function isAdmin() {
        $adminRoles = static::where('level', '>', 98)->get();

        return $adminRoles->contains($this);
    }
}
