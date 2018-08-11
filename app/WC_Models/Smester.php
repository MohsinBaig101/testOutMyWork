<?php

namespace App\WC_Models;

use Illuminate\Database\Eloquent\Model;

class Smester extends Model
{
    protected $table = "wc_smesters";
    public $timestamps = false;
    protected $fillable = ['semester_name','semester_no','client_id'];
}
