<?php

namespace App\Http\Controllers\WC_Programs;

use App\WC_Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Controllers\Controller;

class ProgramsController extends Controller
{

    public function index()
    {
        $client_id = Input::get('client_ids');
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
         $program_obj['programs'] = Program::where('client_id',$client_id)->get(); 
        
        if(!empty($program_obj['programs']))
        {
            return response()->json($program_obj);
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
          $client_id = Input::get('client_id');
            $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
            $request->merge(['client_id'=>$client_id]);
            $program_obj = new Program($request->all());
            $is_saved = $program_obj->save();
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
    public function edit($id)
    {
         if($id)
        {
             $program_obj = Program::find($id);
              
             if($program_obj)
            {
                  return response()->json($program_obj);
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
          $program = Program::find($id);
        if($program)
        {
            $client_id = Input::get('client_id');
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
        $request->merge(['client_id'=>$client_id]);
            $program->update($request->all());
            $arr['success'] = "Information has been saved successfully";
            
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
        $program = Program::find($id);
        $client_id = Input::get('client_id');
        if($program) {
 
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
            $is_deleted = $program->delete();
            
            if($is_deleted)
            {
                  
                 $arr['success'] = "Information has been deleted successfully.";
                 $arr['programs'] = Program::where('client_id',$client_id);
            }

           
        }
        else{
               // flash('User can not be deleted.')->error()->important();
                $arr['error'] = "Information can not be deleted.";

        }

        return response()->json($arr);
    }
   }
