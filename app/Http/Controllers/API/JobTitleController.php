<?php

namespace App\Http\Controllers\API;

use App\JobTitle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobTitleController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        // $rules = array(
        //     'job_title' => 'unique:job_titles'
        // );
        // $params = $request->all();
        // $validator = Validator::make($params, $rules);
        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'server_errors' => $validator->errors()]);
        // } else {
            $user_id = $this->request->user()->id;
            $JobTitle = new JobTitle();
            $JobTitle->fill($request->all());
            $JobTitle->user_id = $user_id;
            $JobTitle->save();
            return response()->json(['success' => true, 'data' => $JobTitle]);
        
    }

    public function update(Request $request, $id) {
        //echo $id;
        $rules = array(
            'job_title' => 'unique:job_titles,id,' . $id
        );
        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'server_errors' => $validator->errors()]);
        } else {
            $JobTitle = JobTitle::find($id);
            if ($JobTitle) {
                $JobTitle->update($request->all());
                return response()->json(['success' => true, 'data' => $JobTitle]);
            } else {
                return response()->json(['success' => false, 'data' => ""]);
            }
        }

        /*$job_title = JobTitle::find($id);
        if ($job_title) {
            $job_title->update($request->all());
            return response()->json(['success' => true, 'data' => $job_title]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }*/
    }

    // public function check_job_title(){
    //     $job_title=$_GET['job_title']; $job_title_id='0';
    //     if($job_title){
    //        $check_job_title=JobTitle::where([['job_title','=',$job_title],['is_delete','=','0']])->get();
    //        if($check_job_title){
    //         foreach ($check_job_title as $key => $value) {
    //                  $job_title_id=$value['id'];

    //         }
    //         if($job_title_id!='0'){
    //             return response()->json(['success' => true, 'data' => $job_title_id]);
    //         }else{
    //                 return response()->json(['success' => false, 'data' => '0']);
    //         }

    //        }
    //     }
    // }

}
