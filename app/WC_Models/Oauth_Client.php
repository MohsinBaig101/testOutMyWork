<?php

namespace App\WC_Models;

use Illuminate\Database\Eloquent\Model;

class Oauth_Client extends Model
{
    protected $table = "oauth_clients";
   // public $timestamps = false;
    protected $fillable = ['id','secret'];
}
