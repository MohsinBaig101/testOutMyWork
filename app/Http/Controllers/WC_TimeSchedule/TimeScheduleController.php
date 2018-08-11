<?php

namespace App\Http\Controllers\WC_TimeSchedule;

use App\User;
use App\WC_Models\CourseAssign;
use App\WC_Models\Course;
use App\WC_Models\Program;
use App\WC_Models\Smester;
use App\WC_Models\Room;
use App\WC_Models\TeacherAvailabilty;
use App\WC_Models\TimeScheduleHandle;
 

use App\WC_Models\UserInfo;
use App\WC_Models\WeakDay;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TimeScheduleController extends Controller
{
     public function index()
    {
         $client_id = Input::get('client_id');
         $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
        $assign_obj['instructors'] = User::where('fk_db_key',2)->where('client_id',$client_id)->get(); 
        $assign_obj['courses'] = Course::where('client_id',$client_id)->get(); 
        $assign_obj['program'] = Program::where('client_id',$client_id)->get(); 
        $assign_obj['smester'] = Smester::where('client_id',$client_id)->get(); 
        
        if(!empty($assign_obj['instructors']) && !empty($assign_obj['courses']) && !empty($assign_obj['program'])&& !empty($assign_obj['smester']))
        {
            $assign_obj['success'] = "Information fetch successfully";
            return response()->json($assign_obj);
        }
        else
        {
            $arr['error'] = "Information cannot found..";
            return response()->json($arr);
        }
    }
    
    
    public function timeschedule(Request $request)
    {
       // dd($request->all());
         $client_id = Input::get('client_id');
         $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
         $program_id = $request->program_id;
         $smester_id = $request->smester_id;
         $course_id = $request->course_id;
         $room_id = $request->room;
         $weak_day_id = $request->weak_day_id;
         $user_id = $request->teacher_name;
         $start_time = $request->class_start_time;
         $end_time = $request->class_end_time;
         
         
         
         $program_obj = Program::where(['program_name' => $program_id,'client_id' => $client_id])->first();
         $program_ids = $program_obj->id;
         $smester_obj = Smester::where(['semester_no' => $smester_id,'client_id' => $client_id])->first();
         $smester_ids = $smester_obj->id;
         $course_obj = Course::where(['course_name' => $course_id,'client_id' => $client_id])->first();
         $course_ids = $course_obj->id;
         $room_obj = Room::where(['room_name' => $room_id,'client_id' => $client_id])->first();
         $room_ids = $room_obj->id;
         $user_obj = User::where(['name' => $user_id,'client_id' => $client_id])->first();
         $user_ids = $user_obj->id;
         
         $start_times = explode(",",$start_time);
         $start_tim = $start_times[1]." ".$start_times[0];
         //echo date('h:i:s a m/d/Y', strtotime($start_time));exit;
         $end_times = explode(",",$end_time);
         $end_tim = $end_times[1]." ".$end_times[0];
        //dd($end_times);
         $request->merge(['client_id'=>$client_id]);
         $request->merge(['user_id'=>$user_ids]);
         $request->merge(['room_id'=>$room_ids]);
         $request->merge(['smester_id'=>$smester_ids]);
         $request->merge(['course_id'=>$course_ids]);
         $request->merge(['program_id'=>$program_ids]);
         $request->merge(['class_start_time'=>$start_tim]);
         $request->merge(['class_end_time'=>$end_tim]);
         
         $date_compare_teacher_start = date('Y-m-d\TH:i:s', strtotime($start_tim));
         $date_compare_teacher_end   = date('Y-m-d\TH:i:s', strtotime($end_tim));
        // $exists = TimeScheduleHandle::whereBetween('class_start_time', ["$start_tim","$end_tim"])->first();
//         if($exists)
//         {
//             echo "value ha jy";exit;
//         }
//         else
//         {
//             echo "value koi ni";exit;
//         }
//         $timeschedule = new TimeScheduleHandle($request->all());
//         $timeschedule->save();
         $timeschedule = TimeScheduleHandle::where('room_id', '=', $room_ids)->where('weak_day_id',$weak_day_id)
            ->where(function ($query)use ($start_tim,$end_tim) {
                $query->whereBetween('class_start_time', [$start_tim,$end_tim]);
            }); 
            print_r($timeschedule);exit;
          if($timeschedule)
         {
             echo "value ha jy";exit;
         }
         else
         {
             echo "value koi ni";exit;
         }
//            })->where(function ($query)use ($start_tim,$end_tim) {
//                $query->whereBetween('class_end_time', [$start_tim,$end_tim]);
//                   
//            })->first();
//            if($timeschedule)
//            {
//                echo "ha jy Room";exit;
//            }
//            else
//            {
//                echo "koi ni";
//            }
//            else
//            {
//                echo $weak_day_id;
//                echo "koi ni";exit;
               // echo $user_ids;exit;
//            $teacher_availabilty = TeacherAvailabilty::where('user_id', '=', $user_ids)->where('weak_day_id',$weak_day_id)
//            ->where(function ($query)use ($date_compare_teacher_start,$date_compare_teacher_end) {
//                $query->where('teach_entering_time', '<=', $date_compare_teacher_start)
//                      ->where('teach_leaving_time', '>=', $date_compare_teacher_end);
//            })->first();
//              if($teacher_availabilty)
//            {
//                echo "ha jy";exit;
//            }
//            else
//            {
//                echo "koi ni";exit;
//            }
//            }
         
         
         
//         $teacher_availabilty = TeacherAvailabilty::where('user_id', '=', $user_ids)->where('weak_day_id',$weak_day_id)
//            ->where(function ($query)use ($date_compare_teacher_start,$date_compare_teacher_end) {
//                $query->where('teach_entering_time', '<=', $date_compare_teacher_start)
//                      ->where('teach_leaving_time', '>=', $date_compare_teacher_end);
//            })->first();
//            
//              if($teacher_availabilty)
//            {
//                echo "lec ha wy";
//            }
//            else
//            {
//                echo "koi ni";
//            }
        // print_r($program_id." ::".$smester_id."::".$course_id."::".$weak_day_id."::".$room_id."::".$user_id);
         
    }
    
    public function detail_assign_course()
    {
         $client_id = Input::get('client_id');
         $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
         $assign_obj = CourseAssign::where('client_id',$client_id)->get();
         $data['teacher'] = $assign_obj->user;
         $data['course'] = $assign_obj->course;
         $data['program'] = $assign_obj->program;
         $data['semester'] = $assign_obj->semester;
         return response()->json($data);
         
    }
    
    
    public function assign_detail(Request $request)
    {
        $client_id = Input::get('client_id');
         $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
        $arr;
        $info_obj = CourseAssign::where('client_id',$client_id)->get();
          
         foreach($info_obj as $key=>$value)
        {
            $arr['data'][$key][] = $value->user->name;
            $arr['data'][$key][] = $value->course->course_name;
            $arr['data'][$key][] = $value->program->program_name;
            $arr['data'][$key][] = $value->semester->semester_no;
            $arr['data'][$key][] = $value->id;
        }
        if(!empty($arr['data']))
        {
           return response()->json($arr);
        }
        else
        {
            $arr['error'] = "Some thing wrong";
            return response()->json($arr);
        }
        
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    //Store all the Course list
    public function store(Request $request)
    {
//        print_r($request->all());
        
            $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($request->client_id);
            $request->merge(['client_id'=>$client_id]); 
            $course_obj = new CourseAssign($request->all());
            $is_saved = $course_obj->save();
            if($is_saved)
            {
                $arr['success'] = "Information has been insered successfully";
                return response()->json($arr);
            }
            else
            {
                $arr['error'] = "Information cannot insered successfully";
                return response()->json($arr);
            }

       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        if($id)
        {
             $course_obj['actual'] = CourseAssign::find($id);
               
             if($course_obj)
            {
                  $client_id = Input::get('client_id');
                  $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
                  $course_obj['instructors'] = User::where('fk_db_key',2)->where('client_id',$client_id)->get(); 
                  $course_obj['courses'] = Course::where('client_id',$client_id)->get(); 
                  $course_obj['program'] = Program::where('client_id',$client_id)->get(); 
                  $course_obj['smester'] = Smester::where('client_id',$client_id)->get(); 
        
                 
                     $course_obj['success'] = "Information fetch successfully";
                    
                 
                     return response()->json($course_obj);
            }
            else
            {
               $arr['error'] = "Information cannot been found..";
                return response()->json($arr);
            }
             
        }
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // dd($request->all());
        $course = CourseAssign::find($id);
        if($course)
        {
            $course->update($request->all());
            $arr['success'] = "hu gai saved";
            
            return self::index();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      //  dd("hello");
        $course = CourseAssign::find($id);

        if($course) {
           
            $is_deleted = $course->delete();
            
            if($is_deleted)
            {
                  
                 $arr['success'] = "Instructor has been deleted successfully.";
                 $arr['courses'] = Course::all();
            }

           
        }
        else{
               // flash('User can not be deleted.')->error()->important();
                $arr['error'] = "Instructor can not be deleted.";

        }

        //$arr['error'] = "Instructor Information cannot been found";
        return response()->json($arr);
    }
}
