<?php

namespace App\Http\Controllers\API;

use App\CarMake;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MakeController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return CarMake::all();
    }

    public function show($id) {
        $data = CarMake::find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $make = new CarMake();
        $make->fill($request->all());
        $make->user_id = $user_id;
        $make->save();
        return response()->json(['success' => true, 'data' => $make]);
    }

    public function update(Request $request, $id) {
        $make = CarMake::find($id);
        if ($make) {
            $make->update($request->all());
            return response()->json(['success' => true, 'data' => $make]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    // public function check_make_exist(){
    //     $make=$_GET['make']; $make_id='0';
    //     if($make){
    //        $check_make=CarMake::where([['make','=',$make],['is_delete' ,'=','0']] )->get(); 
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
