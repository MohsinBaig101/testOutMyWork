<?php

namespace App\Http\Controllers\WC_Smester;

use App\WC_Models\Smester;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\WC_Models\Oauth_Client;

use App\Http\Controllers\Controller;

class SmesterController extends Controller
{

    public function index()
    {
      //  Request $request
        $client_id = Input::get('client_ids');
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
        $semester_obj['semesters'] = Smester::where('client_id',$client_id)->get(); 
        
        if(!empty($semester_obj['semesters']))
        {
            return response()->json($semester_obj);
        }
        else
        {
            $arr['error'] = "Information cannot found..";
            return response()->json($arr);
        }
    }
    
    
    
    
    public function token_verification($id)
    {
        
        $encrypt_client_id = $id;
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($id);
        $user = Oauth_Client::where('id',$client_id)->first();
          if(!empty($user))
          {
              $uses['client_id'] = $encrypt_client_id;
              
              $uses['secret'] = $user->secret;
              return response()->json($uses);
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
      
           $client_id = Input::get('client_id');
            $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
            $request->merge(['client_id'=>$client_id]);
            $smester_obj = new Smester($request->all());
            $is_saved = $smester_obj->save();
            if($is_saved)
            {
                $arr['success'] = "Information has been insered successfully";
                return response()->json($arr);
            }
            else
            {
                $arr['error'] = "Information cannot  saved";
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
    public function edit($id, Request $request)
    {
        if($id)
        {
             $semester_obj = Smester::where(['id' => $id])->get();
              
             if($semester_obj)
            {
                  return response()->json($semester_obj);
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
       
         $semester = Smester::find($id);
        if($semester)
        {
            $client_id = Input::get('client_id');
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
        $request->merge(['client_id'=>$client_id]);
            $semester->update($request->all());
            $arr['success'] = "hu gai saved";
            
            return self::index($request);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
          $semester = Smester::find($id);
          $client_id = $request->client_id;
        if($semester) {
                   $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
            $is_deleted = $semester->delete();
            
            if($is_deleted)
            {
                  
                 $arr['success'] = "Information has been deleted successfully.";
                 $arr['semesters'] = Smester::where('client_id',$client_id)->get();
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
