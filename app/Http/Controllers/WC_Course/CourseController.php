<?php

namespace App\Http\Controllers\WC_Course;

use App\User;
use App\WC_Models\Course;
 

use App\WC_Models\UserInfo;
use App\WC_Models\WeakDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function __construct() {
        $this->middleware('api');
    }

    public function index()
    {
        $client_id = Input::get('client_ids');

        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
        $course_obj['courses'] = Course::where('client_id',$client_id)->get(); 
        
        if(!empty($course_obj['courses']))
        {
            return response()->json($course_obj);
        }
        else
        {
            $arr['error'] = "Information cannot found..";
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
       if(!empty($request->course_code) && !empty($request->course_name))
       {
             $client_id = Input::get('client_id');
            $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
            $request->merge(['client_id'=>$client_id]);
            $course_obj = new Course($request->all());
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
             $course_obj = Course::find($id);
              
             if($course_obj)
            {
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
        $course = Course::find($id);
        if($course)
        {
        $client_id = Input::get('client_id');
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
        $request->merge(['client_id'=>$client_id]);
            $course->update($request->all());
            $arr['success'] = "Information has been saved";
            
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
        $course = Course::find($id);

        if($course) {
           $client_id = Input::get('client_id');

        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
            $is_deleted = $course->delete();
            
            if($is_deleted)
            {
                  
                 $arr['success'] = "Information has been deleted successfully.";
                 $arr['courses'] = Course::where('client_id',$client_id)->get();
            }

           
        }
        else{
               // flash('User can not be deleted.')->error()->important();
                $arr['error'] = "Information can not be deleted.";

        }

        //$arr['error'] = "Instructor Information cannot been found";
        return response()->json($arr);
    }
}
