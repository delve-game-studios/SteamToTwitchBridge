<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role() {
        return $this->hasOne('App\Role', 'id', 'role_id')->first();
    }

    public function getLevel() {
        return $this->role()->level;
    }

    public function getRole() {
        return $this->role()->title;
    }

    public function setRole(Role $role) {
        $this->update(['role_id' => $role->id]);
    }

    public function services() {
        return $this->belongsToMany('\App\Service', 'user_services', 'user_id', 'service_id');
    }

    public function hasService($slug) {
        $service = \App\Service::where(['slug' => $slug])->first();
        foreach($this->services()->get() as $key => $temp) {
            if($temp->is($service)) return $service;
        }
        return false;
    }

    public function getService($slug) {
        foreach($this->services()->get() as $service) {
            if($service->slug == $slug) {
                return $service;
            }
        }

        return false;
    }

    public function isSubscriber() {
        $isSubscriber = !!$this->role()->is(\App\Role::find(4));
        $isAdmin = $this->isAdmin();
        return $isSubscriber || $isAdmin;
    }

    public function isAdmin() {
        return $this->role()->isAdmin();
    }
}
