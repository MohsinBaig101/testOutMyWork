<?php

namespace App\WC_Models;

use Illuminate\Database\Eloquent\Model;

class WeakDay extends Model
{
    protected $table = "wc_weak_days";
    public $timestamps = false;
    protected $fillable = ['weak_day_name','weak_day_no','client_id'];
}
