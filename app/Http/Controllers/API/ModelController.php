<?php

namespace App\Http\Controllers\API;

use App\CarModel;
use App\CarMake;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModelController extends Controller
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return CarModel::all();
    }

    public function show($id)
    {
        $data = CarModel::find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        //echo "<pre>";print_r($request->all()); die();
        $user_id = $this->request->user()->id;
        $model = new CarModel();
        $model->from_model_year = $request->input('from_model_year');
        if ($request->input('to_model_year')) {
            $model->to_model_year = $request->input('to_model_year');
        }

        $model->model = $request->input('model');
        //$model->engine_type = $request->input('engine_type');
        //$model->liter = $request->input('liter');
        $model->fill($request->all());
        $model->user_id = $user_id;
        $model->save();
        //echo "<pre>";print_r($model); die();
        return response()->json(['success' => true, 'data' => $model]);
    }

    public function update(Request $request, $id)
    {
        $model = CarModel::find($id);
        if ($model) {
            $model->update($request->all());
            return response()->json(['success' => true, 'data' => $model]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    // public function check_model_exist(){
    //     $model=$_GET['model']; $model_id='0';
    //     if($model){                     
    //        $check_model=CarModel::where([['model','=',$model],['is_delete' ,'=','0']])->get(); 
    //        if($check_model){
    //         foreach ($check_model as $key => $value) {
    //                  $model_id=$value['id'];

    //         }
    //         if($model_id!='0'){
    //             return response()->json(['success' => true, 'data' => $model_id]); 
    //         }else{
    //                 return response()->json(['success' => false, 'data' => '0']); 
    //         }

    //        }
    //     }
    // }


    public function get_make_name(Request $request)
    {
        $make_name = '';
        $make_id = $_POST['make_id'];
        if ($make_id) {
            $check_make = CarMake::where([['id', '=', $make_id], ['is_delete', '=', '0']])->get();
            if ($check_make) {
                foreach ($check_make as $key1 => $value1) {
                    $make_name = $value1['make'];
                }
                if (!empty($make_name)) {
                    return response()->json(['success' => true, 'data' => $make_name]);
                } else {
                    return response()->json(['success' => false, 'data' => '']);
                }
            }
        }
    }
    public function get_model(Request $request)
    {
        $make_name = '';
        $make_id = $request->make_id;
        if ($make_id) {
            $check_make = CarModel::where([['make', '=', $make_id], ['is_delete', '=', '0']])->get();
            if ($check_make) {
                // foreach ($check_make as $key1 => $value1) {
                //     $make_name = $value1['model'];
                // }
                // if (!empty($make_name)) {
                // } else {
                // }
                return response()->json(['success' => true, 'data' => $check_make]);
            } else {

                return response()->json(['success' => false, 'data' => '']);
            }
        }
    }
    public function get_car_model_year(Request $request)
    {
        // print_r($request->all());exit;

        $make_name = '';
        $make_id = $request->make_id;
        if ($make_id) {
            $check_make = CarModel::where([['id', '=', $make_id], ['is_delete', '=', '0']])->get();
            if ($check_make) {
                if ($check_make[0]['from_model_year'] > $check_make[0]['to_model_year']) {
                    $years = range($check_make[0]['from_model_year'], $check_make[0]['to_model_year']);
                } else {
                    $years = range($check_make[0]['to_model_year'], $check_make[0]['from_model_year']);
                }
                // echo"<pre>";print_r($years);exit;

                return response()->json(['success' => true, 'data' => $check_make[0]]);
            } else {

                return response()->json(['success' => false, 'data' => '']);
            }
        }
    }
}
