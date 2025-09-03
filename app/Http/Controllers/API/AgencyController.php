<?php

namespace App\Http\Controllers\API;

use App\Agency;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgencyController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $Agency = new Agency();
        $Agency->fill($request->all());
        $Agency->user_id = $user_id;
        $Agency->save();
        return response()->json(['success' => true, 'data' => $Agency]);
    }

    public function update(Request $request, $id) {
        $Agency = Agency::find($id);
        if ($Agency) {
            $Agency->update($request->all());
            return response()->json(['success' => true, 'data' => $Agency]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }
    // public function check_agency(){
    //     $agency=$_GET['agency']; $agency_id='0';
    //     if($agency){
    //        $check_agency=Agency::where([['agency','=',$agency],['is_delete','=','0']])->get(); 
    //        if($check_agency){
    //         foreach ($check_agency as $key => $value) {
    //                  $agency_id=$value['id'];
                     
    //         }
    //         if($agency_id!='0'){
    //             return response()->json(['success' => true, 'data' => $agency_id]); 
    //         }else{
    //                 return response()->json(['success' => false, 'data' => '0']); 
    //         }
            
    //        }
    //     }
    // } 
}
