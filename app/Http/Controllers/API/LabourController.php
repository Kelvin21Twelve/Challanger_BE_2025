<?php

namespace App\Http\Controllers\API;

use App\Labour;
use App\CarModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LabourController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $labours = new Labour();
        $labours->fill($request->all());
        $labours->apply_for_all = $request->input("apply_for_all") ?? 'no';
        $labours->print_adoption = $request->input("print_adoption") ?? 'no';
        $labours->user_id = $user_id;
        $labours->save();
        return response()->json(['success' => true, 'data' => $labours]);
    }

    public function update(Request $request, $id) {
        $labours = Labour::find($id);
        if ($labours) {
            $labours->fill($request->all());
            $labours->apply_for_all = $request->input("apply_for_all") ?? 'no';
            $labours->print_adoption = $request->input("print_adoption") ?? 'no';
            $labours->save();
            return response()->json(['success' => true, 'data' => $labours]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    public function get_labour(Request $request){
        if($request['car_type']){
            $get_model=CarModel::where('model',@$request['car_type'])->first();
            if($get_model){
                $get_labour=Labour::where([['car_type','=',@$get_model->id],['is_delete','=',0]])->get();
                if($get_labour){
                    return response()->json(['success' => true, 'data' => $get_labour]);
                }else{
                    return response()->json(['success' => false, 'data' => ""]);
                }
            }else{
               return response()->json(['success' => false, 'data' => ""]);
            }
        }
    }

}
