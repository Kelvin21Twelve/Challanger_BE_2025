<?php

namespace App\Http\Controllers\API;

use App\CarEngine;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EngineController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        //print_r($request->all()); die();
        $user_id = $this->request->user()->id;
        $engine = new CarEngine();
        $engine->user_id = $user_id;
        $engine->make = $request->make;
        $engine->model = $request->engine_model;
        $engine->engine_type = $request->engine_type;
        if($request->liter){
            $engine->liter = $request->liter;
        }else{
            $engine->liter ="";
        }
        
        $engine->fill($request->all());
        $engine->save();
        //print_r($engine); die();
        return response()->json(['success' => true, 'data' => $engine]);
    }

    public function update(Request $request, $id) {
        $engine = CarEngine::find($id);
        if ($engine) {
            if($request->liter){
                $engine->liter = $request->liter;
            }else{
                $engine->liter ="";
            }
            $engine->update($request->all());
            return response()->json(['success' => true, 'data' => $engine]);
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
