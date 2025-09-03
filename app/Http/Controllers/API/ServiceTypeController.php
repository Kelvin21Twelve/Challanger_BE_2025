<?php

namespace App\Http\Controllers\API;

use App\LabourServiceType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return LabourServiceType::all();
    }

    public function get_labourservicetype(){
        $data= LabourServiceType::where('is_delete','0')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function show($id) {
        $data = LabourServiceType::find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
    	$user_id = $this->request->user()->id;
        $service_type = new LabourServiceType();
        $service_type->type=@$request['type'];
        $service_type->user_id = $user_id;
        $service_type->save();
        return response()->json(['success' => true, 'data' => $service_type]);
    }

    public function update(Request $request, $id) {
        $service_type = LabourServiceType::find($id);
        if ($service_type) {
            $service_type->update($request->all());
            return response()->json(['success' => true, 'data' => $service_type]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    // public function check_make_exist(){
    //     $make=$_GET['make']; $make_id='0';
    //     if($make){
    //        $check_make=LabourServiceType::where([['make','=',$make],['is_delete' ,'=','0']] )->get(); 
    //        if($check_make){
    //         foreach ($check_make as $key => $value) {
    //                  $make_id=$value['id'];
                     
    //         }
    //         if($make_id!='0'){
    //             return response()->json(['success' => true, 'data' => $make_id]); 
    //         }else{
    //                 return response()->json(['success' => false, 'data' => '0']); 
    //         }
            
    //        }
    //     }
    // }

}
