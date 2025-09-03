<?php

namespace App\Http\Controllers\API;

use App\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BrandController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return Brand::all();
    }

    public function show($id) {
        $data = Brand::find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $brand = new Brand();
        $brand->fill($request->all());
        $brand->user_id = $user_id;
        $brand->save();
        return response()->json(['success' => true, 'data' => $brand]);
    }

    public function update(Request $request, $id) {
        $brand = Brand::find($id);
        if ($brand) {
            $brand->update($request->all());
            return response()->json(['success' => true, 'data' => $brand]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    // public function check_check_brand(){
    //     $brand=$_GET['brand']; $brand_id='0';
    //     if($brand){
    //        $check_brand=Brand::where([['brand_name','=',$brand],['is_delete','=','0']])->get(); 
    //        if($check_brand){
    //         foreach ($check_brand as $key => $value) {
    //                  $brand_id=$value['id'];
                     
    //         }
    //         if($brand_id!='0'){
    //             return response()->json(['success' => true, 'data' => $brand_id]); 
    //         }else{
    //                 return response()->json(['success' => false, 'data' => '0']); 
    //         }
            
    //        }
    //     }
    // } 

}
