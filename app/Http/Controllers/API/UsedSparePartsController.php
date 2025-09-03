<?php

namespace App\Http\Controllers\API;

use App\UsedSpareParts;
use App\User;
use App\CarMake;
use App\CarModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class UsedSparePartsController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $make = new UsedSpareParts();
        $make->fill($request->all());
        $make->user_id = $user_id;
        $make->save();
        return response()->json(['success' => true, 'data' => $make]);
    }

    public function update(Request $request, $id) {
        $make = UsedSpareParts::find($id);
        if ($make) {
            $make->update($request->all());
            return response()->json(['success' => true, 'data' => $make]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    public function update_bal(Request $request){
        $spare_part=@$request['spare_part'];
        $used_spr_bal =  UsedSpareParts::where('id', '=',$spare_part)->get();
        if($used_spr_bal){
            $balance=$used_spr_bal[0]['balance']-1;
            $Updatebalance = UsedSpareParts::where('id', '=',$spare_part)->update(['balance' => $balance]);
            if($Updatebalance){
                return response()->json(['success' => true, 'data' => $Updatebalance]);
            }else{
                return response()->json(['success' => false, 'data' => '']);
            }
        }
    }

    public function get_used_spare_parts_data(){

        $used_spare_parts =  UsedSpareParts::where('is_delete','=',0)->where('balance','>',0)->get();
        if($used_spare_parts){
            return response()->json(['success' => true, 'data' => $used_spare_parts]);
        }else{
              return response()->json(['success' => true, 'data' => '']);
        }
    }

    public function update_delete_bal(Request $request){
        $item_name=@$request['item_name'];
        $used_spr_bal =  UsedSpareParts::where('item_name', '=',$item_name)->get();
        if($used_spr_bal){
            $balance=$used_spr_bal[0]['balance']+1;
            $Updatebalance = UsedSpareParts::where('item_name', '=',$item_name)->update(['balance' => $balance]);
            if($Updatebalance){
                return response()->json(['success' => true, 'data' => $Updatebalance]);
            }else{
                return response()->json(['success' => false, 'data' => '']);
            }
        }
    }

    public function index(){
        $data=UsedSpareParts::where('is_delete','=',0)->get();
      if($data){
         foreach ($data as $key => $value) {
                if($value['car_view']){
                  $make=CarMake::find($value['car_view']);
                  if($make){
                    $value['car_view']=$make->make;
                  }
                }
                if($value['car_type']){
                  $model=CarModel::find($value['car_type']);
                  if($model){
                    $value['car_type']=$model->model;
                  }
                }
                
        }
         return response()->json(['success' => true, 'data' => $data]);
      }else{
        return response()->json(['success' => true, 'data' =>'']);
      }
    }


}
