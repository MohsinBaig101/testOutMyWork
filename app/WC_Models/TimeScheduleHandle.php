<?php

namespace App\WC_Models;
use App\User;
use App\WC_Models\Course;
use App\WC_Models\Program;
use App\WC_Models\Smester;
use Illuminate\Database\Eloquent\Model;

class TimeScheduleHandle extends Model
{
    protected $table = "wc_time_scheduling";
    public $timestamps = false;
    protected $fillable = ['course_id','user_id','program_id','smester_id','room_id','class_start_time','class_end_time','weak_day_id','client_id'];
    
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class,'course_id','id','user_id');
    }
    public function program()
    {
        return $this->belongsTo(Program::class,'program_id','id','user_id');
    }
    public function semester()
    {
        return $this->belongsTo(Smester::class,'smester_id','id','user_id');
    }
}
