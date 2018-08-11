<?php

namespace App\Http\Controllers\WC_User;

use App\User;
use App\WC_Models\UserInfo;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function index()
    {
        echo 'hello';
        exit;
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
    
    public function login(Request $request)
    {
        $email = $request->email;
        $user_obj = User::where('email',$email)->first();
        if($user_obj)
        {
            
            if (Hash::check($request->password, $user_obj->password)) { 
                if($user_obj->auth_token)
                {
                $arr['error'] = "You cannot loged in..Please get the token";
                return response()->json($arr);  
                exit;
                }
                $arr['success'] = "You are successfully logged in";
                $arr['login_token'] = \Illuminate\Support\Facades\Crypt::encrypt($user_obj->id);
                $arr['fk_db_key'] = $user_obj->fk_db_key;
               // $arr['id'] = $user_obj->id;
                Session::put('login', $arr['login_token']);
                Session::save();
               // print_r(session('login_token'));exit;
                //$request->session()->put('', );
                return response()->json($arr);
                }else
                {
                //  $arr['success'] = "You are successfully logged in";
                $arr['error'] = "Information cannot matched";
                return response()->json($arr);  
                }
               
        }
        else
        {
           $arr['error'] = "Information cannot matched";
           return response()->json($arr);
        }
        
    }

    //Store all the Course list
    public function store(Request $request)
    {
        
        if(!empty($request->email)) // change with button or $requst->button_name
        {
          // dd($request->all());
            $user_obj = User::where('email',$request->email)->get();
                             //  ->where('password',crypt($request->Password))->get();
           
            if($user_obj->count() > 0)
            {
                 
                //echo $user_obj->password . " ::: ". $request->Password;exit;
                // if(!Hash::check($request->password, $user_obj->password))
                // {
                    $user_obj[1] = "user_logged";
                    return response()->json($user_obj);
               
                
            }
            else
            {
                $arr['error'] = "Information cannot  saved";
                return response()->json($arr);
            }
        }
        else
        {
            $arr['error'] = 'Information cannot saved';
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
