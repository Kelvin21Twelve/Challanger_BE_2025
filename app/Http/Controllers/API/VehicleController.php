<?php

namespace App\Http\Controllers\API;

use App\Vehicle;
use App\JobCard;
use App\Customer;
use App\CarMake;
use App\CarModel;
use App\Agency;
use App\CarColor;
use App\CarEngine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {

        // $rules = array(
        //     //'driver_mobile' => 'required|numeric|digits_between:5,15|unique:vehicles',
        //     'plate_no' => 'required|unique:vehicles'
        // );
        // $params = $request->all();
        // $validator = Validator::make($params, $rules);
        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'server_errors' => $validator->errors()]);
        // } else {
            $user_id = $this->request->user()->id;
            $vehicle = new Vehicle();
            if(is_string($request->input("customer"))){
               $customer_search=Customer::where('cust_name','=',$request->input("customer"))->get();
               foreach ($customer_search as $key => $value) {
                   $customer_id=$value['id'];
               }
            }
            if(is_numeric($request->input("customer"))){
                    $customer_id=$request->input("customer");
            }
            //echo $customer_id;  die();
            if(!empty($customer_id)){

              $vehicle->plate_no =$request->input("plate_no");
              $vehicle->customer =$customer_id;
              $vehicle->car_view =$request->input("car_view");
              $vehicle->car_type =$request->input("car_type");
              $vehicle->car_engine =$request->input("car_engine");
              $vehicle->model =$request->input("model");
              $vehicle->car_make =$request->input("car_make");
              $vehicle->chasis_no =$request->input("chasis_no");
              $vehicle->car_color =$request->input("car_color");
              $vehicle->engine_cc =$request->input("engine_cc");
              $vehicle->driver_name =$request->input("driver_name");
              $vehicle->driver_mobile =$request->input("driver_mobile");
              $vehicle->note =$request->input("note");
              $vehicle->car_engine =$request->input("car_engine");
              $vehicle->user_id = $user_id;
              $vehicle->save();
              $all_vehicle = Vehicle::with(['view'])->with(['type'])->with(['color'])->with(['agency'])->where('is_delete','0')->get();
             
            }
             return response()->json(['success' => true, 'data' => $vehicle,'all_vehicle'=>$all_vehicle]);
        //}
    }

    public function update(Request $request, $id) {
        // $rules = array(
        //     // 'driver_mobile' => 'required|numeric|digits_between:5,15|unique:vehicles,driver_mobile,' . $request->id,
        //     'plate_no' => 'required|unique:vehicles,plate_no,' . $request->id
        // );
        // $params = $request->all();
        // $validator = Validator::make($params, $rules);
        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'server_errors' => $validator->errors()]);
        // } else {
            $vehicle = Vehicle::find($id);
            if ($vehicle) {
                $vehicle->update($request->all());
                $vehicle_obj = Vehicle::with(['view'])->with(['type'])->with(['color'])->with(['customers'])->where(['id' => $id])->first();
                $upadte_array = array(
                    'customer' => (isset($vehicle_obj->customers->cust_name) ? $vehicle_obj->customers->cust_name : ""),
                    'customer_id' => (isset($vehicle_obj->customers->id) ? $vehicle_obj->customers->id : ""),
                    'view' => (isset($vehicle_obj->view->make) ? $vehicle_obj->view->make : ""),
                    'type' => (isset($vehicle_obj->type->model) ? $vehicle_obj->type->model : ""),
                    'model' => (isset($vehicle_obj->model) ? $vehicle_obj->model : ""),
                    'color' => (isset($vehicle_obj->color->color) ? $vehicle_obj->color->color : ""),
                    'plate_no' => (isset($vehicle_obj->plate_no) ? $vehicle_obj->plate_no : "")
                );
                JobCard::where('vehicle', '=', $id)->update($upadte_array);
                return response()->json(['success' => true, 'data' => $vehicle]);
            } else {
                return response()->json(['success' => false, 'data' => ""]);
            } 
        //}
    }

    public function get_job_cards_vehicle(Request $request){
      $id='';
      if($request["cust"]){
          $customer_obj = Customer::where('cust_name','=' , $request["cust"])->get();
          if($customer_obj){
            foreach ($customer_obj as $key => $value) {
               $id=$value['id'];
            }
            if($id){
               $vehicle_obj = Vehicle::where('customer','=' , $id)->get();
                if($customer_obj){
                  return response()->json(['success' => true, 'data' => $vehicle_obj]);
                }else{
                       return response()->json(['success' => false, 'data' => ""]);
                }

            }
          }
      }

    }

    public function index(){
      $data=Vehicle::where('is_delete','=',0)->get();
      if($data){
         foreach ($data as $key => $value) {
                if($value['customer']){
                  $cust=Customer::find($value['customer']);
                  if($cust){
                    $value['customer']=$cust->cust_name;
                    $value['civil_id']=$cust->civil_id;
                    $value['phone']=$cust->phone;
                  }
                }
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
                if($value['car_make']){
                  $agency=Agency::find($value['car_make']);
                  if($agency){
                    $value['car_make']=$agency->agency;
                  }
                }
                if($value['car_engine']){
                  $engine=CarEngine::find($value['car_engine']);
                  if($engine){
                    $value['car_engine']=$engine->engine_type;
                  }
                }
                if($value['car_color']){
                  $color=CarColor::find($value['car_color']);
                  if($color){
                    $value['car_color']=$color->color;
                  }
                }
         }
         return response()->json(['success' => true, 'data' => $data]);
      }else{
        return response()->json(['success' => true, 'data' =>'']);
      }
    }

}
