<?php

namespace App\WC_Models;

use Illuminate\Database\Eloquent\Model;
use App\WC_Models\WeakDay;
use App\User;
use Carbon;


class TeacherAvailabilty extends Model
{
    
    protected $table = "wc_teacher_availabilities";
  //  public $timestamps = false;
    protected $fillable = ['teach_entering_time','teach_leaving_time','weak_day_id','user_id'];
    
    
    public function getDateStartAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d\TH:i');
    }
        
    public function getDateEndAttribute($value)
    {
         return Carbon::parse($value)->format('Y-m-d\TH:i');
    }
     
    public function weak_day()
    {
    	 return $this->belongsTo(WeakDay::class,'weak_day_id','id');
    }
     public function user()
    {
    	 return $this->belongsTo(User::class,'user_id','id');
    }
    
    
}
