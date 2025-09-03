<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Gallery_img;


class LocalizationController extends Controller
{
    
    public function setlang(Request $request){

        if (!empty($request["id"])) {
            \Session::put(['lang' => $lang]);
            return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Success!','redirect_url'=>'/']);
        }else{
            return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Failed!','redirect_url'=>'/']);
        }
        // return redirect()->route('main');
        // return response()->json(['success' => TRUE]);
    }

    public function gallery_img(){
        $datas = Gallery_img::get();
        return view('pages.gallery', ["datas" => $datas]);
    } 
}
