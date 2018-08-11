<?php

namespace App\Http\Controllers\WC_AssignCourses;

use App\User;
use App\WC_Models\CourseAssign;
use App\WC_Models\Course;
use App\WC_Models\Program;
use App\WC_Models\Smester;
 

use App\WC_Models\UserInfo;
use App\WC_Models\WeakDay;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssignCoursesController extends Controller
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
            //dd($arr);
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
