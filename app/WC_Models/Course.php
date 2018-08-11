<?php

namespace App\WC_Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = "wc_courses";
    public $timestamps = false;
    protected $fillable = ['course_name','course_code','client_id'];
}
