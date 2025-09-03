<?php

namespace App\Http\Controllers\API;

use App\InvoiceType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvcTypeController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return InvoiceType::all();
    }

    public function show($id) {
        $data = InvoiceType::find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $invc_type = new InvoiceType();
        $invc_type->type=@$request['type'];
        $invc_type->user_id = $user_id;
        $invc_type->save();
        return response()->json(['success' => true, 'data' => $invc_type]);
    }

    public function update(Request $request, $id) {
        $invc_type = InvoiceType::find($id);
        if ($invc_type) {
            $invc_type->update($request->all());
            return response()->json(['success' => true, 'data' => $invc_type]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    // public function check_make_exist(){
    //     $make=$_GET['make']; $make_id='0';
    //     if($make){
    //        $check_make=InvoiceType::where([['make','=',$make],['is_delete' ,'=','0']] )->get(); 
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
