<?php

namespace App\Http\Controllers\WC_Teacher;

use App\User;
use App\WC_Models\TeacherAvailabilty;
use Validator;
use Illuminate\Support\Facades\Hash;

use App\WC_Models\UserInfo;
use Illuminate\Support\Facades\Input;
use App\WC_Models\WeakDay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 

class TeacherController extends Controller
{

    public function index()
    {
      
        $client_id = Input::get('client_ids');
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
       // $request->merge(['client_id'=>$client_id]);
        $teacher_obj['teacher'] = User::where(['fk_db_key'=>2,'client_id'=>$client_id])->get();
         $teacher_obj['teaher_detail'] = TeacherAvailabilty::all();
         $teacher_obj['weak_days'] = WeakDay::all(); 
          
        // $teacher_obj['logged_name'] = User::where('client_id',$client_id)->first();
         return response()->json($teacher_obj);
    }


    public function teacher_detail_show($id,Request $request)
    {

            if($id)
        {
             $instructor_obj = User::find($id);
              //dd($instructor_obj);
             // dd($instructor_obj->teacher_availabilty->weak_day);
             if($instructor_obj->teacher_availabilty->count() > 0)
            {
                 $response;
                 foreach ($instructor_obj->teacher_availabilty as $key => $user) {
                  $reponse['data'][$key][] = $key+1;
                   $reponse['data'][$key][] = $instructor_obj->name;
                  $reponse['data'][$key][] = date('F j, Y, g:i a', strtotime($user->teach_entering_time));
                  $reponse['data'][$key][] = date('F j, Y, g:i a', strtotime($user->teach_leaving_time));
                  $reponse['data'][$key][] = $user->weak_day->weak_day_name;
                  $reponse['data'][$key][] = $user->user_id;
                  $reponse['data'][$key][] = $user->id;
                    
                 }
                return json_encode($reponse);
           
            }
            else
            {
                $response['error'] = "User Information cannot been found";
                return json_encode($response);
            }
             
        }
    }




    public function teacher_detail($id,Request $request)
    {
            if($id)
        {
                //2020-02-02T02:00
             $instructor_obj['data'] = TeacherAvailabilty::where('id',$id)->first();
              
             if($instructor_obj)
            {
             
                return json_encode($instructor_obj);
           
            }
            else
            {
                $arr['error'] = "User Information cannot been found";
                return response()->json($arr);
            }
             
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
        //echo "ais ma aya ha";exit;
        //saved tacher availabilty accprding user id
        if($request->fk_user_data)
        {
            $user_id = $request->user_id;
              $client_id = Input::get('client_id');
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
            $teacher_detail_obj = new TeacherAvailabilty($request->all());
            $is_saved = $teacher_detail_obj->save();
            
            if($is_saved)
            {
               
       // $request->merge(['client_id'=>$client_id]);
         $arr['teacher'] = User::where(['fk_db_key'=>2,'client_id'=>$client_id])->get();
         $arr['teaher_detail'] = TeacherAvailabilty::all();
         $arr['weak_days'] = WeakDay::all();
                $arr['success'] = "Information has been insered successfully";
                return response()->json($arr);
            }
            else
            {
                $arr['error'] = "Information cannot insered successfully";
                return response()->json($arr);
            }
        }
        
        
       // dd($request->all());
        if(!empty($request->email)) // change with button or $requst->button_name
        {
          
        $rules =  [
            'email' => 'required|unique:users',
        ];
           $validator = Validator::make($request->all(), $rules);
              if ($validator->fails()) {
                  return response()->json(['errors'=>$validator->errors()]);
        }
           $request->merge(['fk_db_key'=> 2]);
           $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($request->client_id);
            $request->merge(['client_id'=>$client_id]);
           // print_r($request->all());exit;
           $request->merge(["password"=>Hash::make($request->password)]);
            $user_obj = new User($request->all());
            $is_saved = $user_obj->save();
          //  dd($is_saved);
            if($is_saved)
            {
                 $arr['success'] = "Information has been insered successfully";
                 return response()->json($arr);

            }
            // if($is_saved && $request->submitDataTeacher)
            // {
            //     unset($is_saved);
            //     $user_info_obj = new UserInfo($request->all());
            //     $is_saved = $user_info_obj->save();
            //     if($is_saved)
            //     {
            //         $arr['success'] = "Information has been insered successfully";
            //         return response()->json($arr);
            //     }
            //     else
            //     {
            //         $arr['error'] = "User Information cannot been added successfully";
            //         return response()->json($arr);
            //     }

            // }
            // else
            // {
            //     $arr['error'] = "Information cannot  saved";
            //     return response()->json($arr);
            // }
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
             $instructor_obj = User::find($id);
              
             if($instructor_obj)
            {
             $teacher_detail = $instructor_obj->teacher_availabilty;
             // if(!$teacher_detail->isEmpty())
             // {
                $arr_user = array_merge($instructor_obj->toArray() , $teacher_detail->toArray());
              
              //  $arr['edit_user'] = "User Information cannot been found";
                return response()->json($arr_user);
           // }
            }
            else
            {
                $arr['error'] = "User Information cannot been found";
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
        $user = User::find($id);
        if($user)
        {
            $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($request->client_id);
            $request->merge(['client_id'=>$client_id]);
            $user->update($request->all());
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
    
    public function delete_instructor_detail($id)
    {
          $availabilty = TeacherAvailabilty::where('id',$id)->first();
          $user_id = $availabilty->user_id;
        if($availabilty) {
           
           
            $is_deleted = $availabilty->delete();
            
            if($is_deleted)
            {
            $instructor_obj = User::find($user_id);
            
             if($instructor_obj->teacher_availabilty->count() > 0)
            {
                 $response;
                 foreach ($instructor_obj->teacher_availabilty as $key => $user) {
                  $reponse['data'][$key][] = $key+1;
                   $reponse['data'][$key][] = $instructor_obj->name;
                  $reponse['data'][$key][] = date('F j, Y, g:i a', strtotime($user->teach_entering_time));
                  $reponse['data'][$key][] = date('F j, Y, g:i a', strtotime($user->teach_leaving_time));
                  $reponse['data'][$key][] = $user->weak_day_id;
                  $reponse['data'][$key][] = $user->user_id;
                  $reponse['data'][$key][] = $user->id;
                    
                 }
               
                 $reponse['success'] = "Information has been deleted successfully.";
                 return json_encode($reponse);
            }

           
        }
        else{
                 $arr['error'] = "Information can not be deleted.";
                      return response()->json($arr);

        }

     
    }
    }




    public function destroy($id)
    {
      //  dd("hello");
        $user = User::find($id);
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt(Input::get('client_id'));
          //  $request->merge(['client_id'=>$client_id]);
        if($user) {
            if($user->teacher_availabilty->count() > 0)
            {
                
                $user->teacher_availabilty()->delete();

            }
            $is_deleted = $user->delete();
            
            if($is_deleted)
            {
                  $client_id = Input::get('client_ids');
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
       // $request->merge(['client_id'=>$client_id]);
        $arr['teacher'] = User::where(['fk_db_key'=>2,'client_id'=>$client_id])->get();
         $arr['teaher_detail'] = TeacherAvailabilty::all();
         $arr['weak_days'] = WeakDay::all();
                 $arr['success'] = "Instructor has been deleted successfully.";
            }

           
        }
        else{
                flash('User can not be deleted.')->error()->important();
                $arr['error'] = "Instructor can not be deleted.";

        }

        //$arr['error'] = "Instructor Information cannot been found";
        return response()->json($arr);
    }
}
