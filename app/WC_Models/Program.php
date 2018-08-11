<?php

namespace App\WC_Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = "wc_programs";
    public $timestamps = false;
    protected $fillable = ['program_name','client_id'];
}
