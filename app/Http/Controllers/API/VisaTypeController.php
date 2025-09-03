<?php

namespace App\Http\Controllers\API;

use App\VisaType;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VisaTypeController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return VisaType::all();
    }

    public function show($id) {
        $VisaType = VisaType::find($id);
        return response()->json(['success' => true, 'data' => $VisaType]);
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $VisaType = new VisaType();
        $VisaType->fill($request->all());
        $VisaType->user_id = $user_id;
        $VisaType->save();
        return response()->json(['success' => true, 'data' => $VisaType]);
    }

    public function update(Request $request, $id) {
        $VisaType = VisaType::find($id);
        if ($VisaType) {
            $VisaType->update($request->all());
            return response()->json(['success' => true, 'data' => $VisaType]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    } 

    // public function check_VisaTypeController_exist(){
    //     $visa_type=$_GET['visa_type']; $visa_type_id='0';
    //     if($visa_type){
    //        $check_visa_type=VisaType::where([['visa_type','=',$visa_type],['is_delete','=','0']])->get(); 
    //        if($check_visa_type){
    //         foreach ($check_visa_type as $key => $value) {
    //                  $visa_type_id=$value['id'];
                     
    //         }
    //         if($visa_type_id!='0'){
    //             return response()->json(['success' => true, 'data' => $visa_type_id]); 
    //         }else{
    //                 return response()->json(['success' => false, 'data' => '0']); 
    //         }
            
    //        }
    //     }
    // }

    public function update_visa_end_date(Request $request){
       $end_date = $this->request->vs_end_date;  
       $id =$this->request->id; 
       if(!empty($end_date) && !empty($id)){
            $update_visa_end_date = User::where('id',$id)->update(['visa_end' => $end_date]);
            /*echo $update_visa_end_date; die();*/
            if($update_visa_end_date){
                    return response()->json(['success' => true, 'data' => $update_visa_end_date]); 
                }/*else{
                    return response()->json(['success' => false, 'data' => '']);
                }*/
        }
    }

}
