<?php

namespace App\Http\Controllers\API;

use App\NewSpareParts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\NewSpareImport;
use App\SparePartsReturn;
use App\Notification;
use Excel;

class NewSparePartsController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        $rules = array(
            'item_code' => 'required'
        );
        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'server_errors' => $validator->errors()]);
        } else {
            if(!empty($request['item_code'])){
                $if_exist=NewSpareParts::where([['is_delete','=',0],['item_code','=',$request['item_code']]])->count();
                if($if_exist > 0){
                    return response()->json(['success' => false, 'server_errors' => ["error"=>"Item code already exist"]]); 
                }else{
                        $user_id = $this->request->user()->id;
                        $NewSpareParts = new NewSpareParts();
                        $NewSpareParts->fill($request->all());
                        $NewSpareParts->user_id = $user_id;
                        $NewSpareParts->supplier = $request->supplier;
                        $NewSpareParts->save();
                        return response()->json(['success' => true, 'data' => $NewSpareParts]);       
                }
            }
            
        }
    }

    public function update(Request $request, $id) {
        // $rules = array(
        //     'item_code' => 'required|numeric|unique:new_spare_parts,item_code,' . $request->id
        // );
        // $params = $request->all();
        // $validator = Validator::make($params, $rules);
        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'server_errors' => $validator->errors()]);
        // } else {
            $NewSpareParts = NewSpareParts::find($id);
            if ($NewSpareParts) {
                $NewSpareParts->supplier = $request->supplier;
                $NewSpareParts->update($request->all());
                return response()->json(['success' => true, 'data' => $NewSpareParts]);
            } else {
                return response()->json(['success' => false, 'data' => ""]);
            }
        //}
    }

    // public function update_count_bal(Request $request){
    //     $Updatebalance='';
    //     $data = $request->all();
    //     $items=$data['myData']["item_data"];
    //     //$items = $request->input("item_data");
    //     foreach ($items as $value) {
    //         $new_spare_parts=NewSpareParts:: where('item_code',$value['item_code'])->get();
    //         if($new_spare_parts){
    //             foreach($new_spare_parts as $value1){
    //                $available_balance= $value1['balance'] - $value['quantity'];
    //                $Updatebalance = NewSpareParts::where('item_code', '=',$item_code)->update(['balance' => $available_balance]);
    //             }
    //         }
    //     }

    //     if($Updatebalance){
    //         return response()->json(['success' => true, 'Updatebalance' => $Updatebalance]);
    //     }else{
    //         return response()->json(['success' => false, 'data' => '']);
    //     } 
        
    // // }
    
    public function check_min_limit(){   
        $new_spr_limit =  NewSpareParts::whereColumn('min_limit' ,'=','balance')->where('is_delete','=',0)->get();
        
        
        if($new_spr_limit){
            return response()->json(['success' => true, 'new_spr_limit' => $new_spr_limit]);
        }else{
            return response()->json(['success' => false, 'data' => '']);
        } 
    }

    public function newspareexcel_add(Request $request){
        $path = $request->file('file')->getRealPath();
        $data = Excel::import(new NewSpareImport, $request->file('file'));
        if($data){
             return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Excel Data Imported successfully.','data'=>$data]);
        }
    }

    Public function get_supplier_new_spare_details(Request $request){
        $data=NewSpareParts::where('item_code',$request->item_code)->where('is_delete',0)->first();
        if($data){
            return response()->json(['success' => true, 'data' => $data]);
        }else{
            return response()->json(['success' => false, 'data' => '']);
        }
    }

}
