<?php

namespace App\Http\Controllers\WC_Room;

use App\WC_Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use App\Http\Controllers\Controller;

class RoomsController extends Controller
{
  public function index()
    {
      
      //  Request $request
        $client_id = Input::get('client_ids');
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
        $room_obj['rooms'] = Room::where('client_id',$client_id)->get(); 
        
        if(!empty($room_obj['rooms']))
        {
            return response()->json($room_obj);
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
            $room_obj = new Room($request->all());
            $is_saved = $room_obj->save();
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
             $room_obj = Room::where(['id' => $id])->first();
             //$client_id = $room_obj->client_id;
            // $room = Room::where('client_id', $client_id)->first();

             // 'client_id' => $request->client_id])->get();
              
             if($room_obj)
            {
                  return response()->json($room_obj);
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
       
         $room = Room::find($id);
        if($room)
        {
             $client_id = Input::get('client_id');
        $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);
        $request->merge(['client_id'=>$client_id]);
            $room->update($request->all());
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
          $semester = Room::find($id);
          $client_id = Input::get('client_id');
        if($semester) {
            $client_id =  \Illuminate\Support\Facades\Crypt::decrypt($client_id);

            $is_deleted = $semester->delete();
            
            if($is_deleted)
            {
                  
                 $arr['success'] = "Information has been deleted successfully.";
                 $arr['semesters'] = Room::where('client_id',$client_id)->get();
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
