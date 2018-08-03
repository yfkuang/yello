<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['firebase_id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    public function leads()
    {
        return $this->hasMany('App\Lead');
    }
	
	public function leadSources()
    {
        return $this->hasMany('App\LeadSource');
    }

}
