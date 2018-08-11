<?php

namespace App\WC_Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = "wc_rooms";
    public $timestamps = false;
    protected $fillable = ['room_name','fk_db_key','client_id'];
}
