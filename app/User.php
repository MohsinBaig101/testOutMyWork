<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasAPITokens;
use App\WC_Models\TeacherAvailabilty;
use App\WC_Models\Oauth_Client;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','fk_db_key','client_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token','password',
    ];

    public function teacher_availabilty()
    {
        return $this->hasMany(TeacherAvailabilty::class,'user_id','id');
    }
    public function auth_token()
    {
        return $this->belongsTo(Oauth_Client::class,'id','user_id');
    }
}
