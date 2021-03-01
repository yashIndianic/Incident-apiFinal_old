<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Incident extends Authenticatable {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'category_id', 'comments', 'incident_date'
    ];
    
    public function locationsss()
    {
        return $this->hasOne(Location::class, 'incident_id', 'id');
    }
    
    public function people(){
    return $this->hasMany(User::class, 'incident_id');
}


}
