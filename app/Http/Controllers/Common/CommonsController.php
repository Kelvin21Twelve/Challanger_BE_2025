<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Mail;
use Input, Redirect, Session, Response, DB;
use Illuminate\Support\Facades\Auth;
class CommonsController extends Controller
{

    public function delete(Request $request)
    {
        $model = $request['model'];
        $user = Auth::user();
        $valid = "0";
        if($user->hasRole('admin')){
            $valid = "1";
        }
        if($valid == "0"){
            return response()->json(['success' => FALSE, 'op' => 'delete', 'msg_type' => 'error', 'msg' => 'Invalid Access!!']);
        }
        $id = $request['id'];
        if ($model) {
            $mod_name = '\\App\\' . $model;
            $delete_flag = $mod_name::find($id);
            if ($delete_flag->delete()) {
                return response()->json(['success' => TRUE, 'op' => 'delete', 'msg_type' => 'success', 'msg' => $model . ' deleted successfully!!']);
            } else {
                return response()->json(['success' => FALSE, 'op' => 'delete', 'msg_type' => 'error', 'msg' => $model . ' deletion Failed!!']);
            }
        } else {
            return response()->json(['success' => FALSE, 'op' => 'delete', 'msg_type' => 'error', 'msg' => $model . ' model not found!!']);
        }
    }


     public function listing(Request $request)
    {
        $user = Auth::user();
        $model = $request['model'];
        $valid = "0";
        if($user->hasRole('admin')){
            $valid = "1";
        }
        if($valid == "0"){
            return response()->json(['success' => FALSE, 'op' => 'listing', 'msg_type' => 'error', 'msg' => 'Invalid Access!!']);
        }
        $page = $request['page'];
        $size = $request['size'];
        $select = $request['select'];
        $wkey = $request['wkey'];
        $wcomp = $request['wcomp'];
        $wval = $request['wval'];
        if(!empty($select)){
            $aselect = explode(",",$select);
        }else{
            $aselect = ['id'];
        }
        if ($model) {
            $mod_name = '\\App\\' . $model;
            $data_obj = $mod_name::orderBy('created_at', 'desc');
            if(!empty($wkey) && !empty($wcomp) && !empty($wval)){
                if($wcomp == "IN"){
                    $data_obj->whereIn($wkey, explode(",",$wval) );
                }elseif($wcomp == "NOTIN"){
                    $data_obj->whereNotIn($wkey, explode(",",$wval) );
                }elseif($wcomp == "WILDLIKE"){
                    $data_obj->where($wkey, 'like', '%' . $wval . '%');
                }else{
                    $data_obj->where($wkey, $wcomp, $wval);
                }
            }
            $data_obj = call_user_func_array(array($data_obj, "select"), $aselect);
            $data_obj = $data_obj->paginate($size);
            if ($data_obj) {
                foreach ($data_obj as $okey => $ovalue) {
                    $id = $ovalue->id;
                    $ovalue->enc_id = en_de_crypt($id, 'e');
                    $data_obj[$okey] = $ovalue;
                }
                return response()->json(['success' => TRUE, 'op' => 'listing', 'msg_type' => 'success', 'msg' => 'Record found successfully!!', 'data' => $data_obj]);
            } else {
                return response()->json(['success' => FALSE, 'op' => 'listing', 'msg_type' => 'error', 'msg' => 'No record found!!']);
            }
        } else {
            return response()->json(['success' => FALSE, 'op' => 'listing', 'msg_type' => 'error', 'msg' => 'Something went wrong!!']);
        }
    }

    public function iupdate(Request $request)
    {
        $model = $request['model'];
        $user = Auth::user();
        $valid = "0";
        if($user->hasRole('admin')){
            $valid = "1";
        }
        if($valid == "0"){
            return response()->json(['success' => FALSE, 'op' => 'delete', 'msg_type' => 'error', 'msg' => 'Invalid Access!!']);
        }
        $adata = $request['adata'];
        $id = $request['id'];
        if( $model && $adata ) {
            if (!empty($id)) {
                $id = en_de_crypt($id, 'd');
                $mod_name = '\\App\\' . $model;
                $mod_data = $mod_name::findorfail($id);
                if(!empty($adata)){
                    foreach ($adata as $key => $value) {
                        $mod_data->$key = $value;
                    }
                }
                if ($mod_data->update()) {
                    return response()->json(['success' => TRUE,'op'=>'update','msg_type'=>'success','msg'=>'Operation has been updated successfully!','id' => $id, 'adata' => $adata]);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'update','msg_type'=>'error','msg'=>'Operation Updation failed!']);
                }
            }else{
                $mod_name = '\\App\\' . $model;
                $mod_data = new $mod_name;
                if(!empty($adata)){
                    foreach ($adata as $key => $value) {
                        $mod_data->$key = $value;
                    }
                }
                if ($mod_data->save()) {
                    $id = en_de_crypt($mod_data->id, 'e');
                    return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Operation has been added successfully!','id' => $id, 'adata' => $adata]);
                }else{
                    return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Operation Insertion failed!']);
                }
            }
        } else {
            return response()->json(['success' => FALSE, 'op' => 'listing', 'msg_type' => 'error', 'msg' => 'Something went wrong!!']);
        }
    }

}