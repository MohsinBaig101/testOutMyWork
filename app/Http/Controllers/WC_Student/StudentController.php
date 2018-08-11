<?php

namespace App\Http\Controllers\WC_Student;

use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
     public function index()
    {
         $client_id = Input::get('client_ids');
         $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);

        $student_obj['students'] = User::where(['fk_db_key' => 3,'client_id'=>$client_id])->get(); 
        
        if(!empty($student_obj['students']))
        {
            return response()->json($student_obj);
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
       if(!empty($request->name) && !empty($request->password))
       {
              $request->merge(["password"=>Hash::make($request->password)]);
              $client_id = Input::get('client_id');
               $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
              $request->merge(["client_id"=>$client_id]);

                 $student_obj = new User($request->all());
                 $is_saved = $student_obj->save();
             
           
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
             $course_obj = User::find($id);
              
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
        $client_id = Input::get('client_id');
             $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
        $student = User::find($id);
        if($student)
        {
             
             $request->merge(['client_id'=>$client_id]);
            $student->update($request->all());
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
    public function destroy(Request $request,$id)
    {
        //dd("hello");
        $user = User::find($id);

        if($user) {
           $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($request->client_id);
            $request->merge(['client_id'=>$client_id]);
            $is_deleted = $user->delete();
            
            if($is_deleted)
            {
                  
                 $arr['success'] = "Information has been deleted successfully.";
                 $arr['students'] = User::where(['fk_db_key' => 3,'client_id' => Input::get('client_id')])->get();
            }

           
        }
        else{
               // flash('User can not be deleted.')->error()->important();
                $arr['error'] = "Information can not be found.";

        }

        //$arr['error'] = "Instructor Information cannot been found";
        return response()->json($arr);
    }
}

