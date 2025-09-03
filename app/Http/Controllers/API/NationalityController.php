<?php

namespace App\Http\Controllers\API;

use App\Nationality;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NationalityController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $national = new Nationality();
        $national->fill($request->all());
        $national->user_id = $user_id;
        $national->save();
        return response()->json(['success' => true, 'data' => $national]);
    }

    public function update(Request $request, $id) {
        $national = Nationality::find($id);
        if ($national) {
            $national->update($request->all());
            return response()->json(['success' => true, 'data' => $national]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    // public function check_nationality_exist(){
    //     $nationality=$_GET['nationality']; $nationality_id='0';
    //     if($nationality){
    //        $check_nationality=Nationality::where('nationality','=',$nationality)->get();
    //        if($check_nationality){
    //         foreach ($check_nationality as $key => $value) {
    //                  $nationality_id=$value['id'];

    //         }
    //         if($nationality_id!='0'){
    //             return response()->json(['success' => true, 'data' => $nationality_id]);
    //         }else{
    //                 return response()->json(['success' => false, 'data' => '0']);
    //         }

    //        }
    //     }
    // }

    public function get_nationality(){
       $nationality=Nationality::where('is_delete','=','0')->get();
       if($nationality){
    //   print_r(gettype(response()->json(['success' => true, 'data' => $nationality])));exit;
          return response()->json(['success' => true, 'data' => $nationality]);
          
       }
    }

}
