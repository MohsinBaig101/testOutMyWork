<?php

namespace App\WC_Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = "wc_user_infos";
    public $timestamps = false;
    protected $fillable = ['first_name','last_name','fk_user_id'];
}
