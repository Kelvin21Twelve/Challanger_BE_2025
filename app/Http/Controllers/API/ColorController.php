<?php

namespace App\Http\Controllers\API;

use App\CarColor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ColorController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return CarColor::all();
    }

    public function show($id) {
        $data = CarColor::find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $color = new CarColor();
        $color->fill($request->all());
        $color->user_id = $user_id;
        $color->save();
        return response()->json(['success' => true, 'data' => $color]);
    }

    public function update(Request $request, $id) {
        $color = CarColor::find($id);
        if ($color) {
            $color->update($request->all());
            return response()->json(['success' => true, 'data' => $color]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    // public function check_color_exist(){
    //     $color=$_GET['color']; $color_id='0';
    //     if($color){
    //        $check_color=CarColor::where([['color','=',$color],['is_delete','=','0']])->get(); 
    //        if($check_color){
    //         foreach ($check_color as $key => $value) {
    //                  $color_id=$value['id'];
                     
    //         }
    //         if($color_id!='0'){
    //             return response()->json(['success' => true, 'data' => $color_id]); 
    //         }else{
    //                 return response()->json(['success' => false, 'data' => '0']); 
    //         }
            
    //        }
    //     }
    // }

}
