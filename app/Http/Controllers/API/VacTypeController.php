<?php

namespace App\Http\Controllers\API;

use App\VacType;
use App\UsersVacations;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use DateTime;

class VacTypeController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return VacType::all();
    }

    public function show($id) {
        $VacType = VacType::find($id);
        return response()->json(['success' => true, 'data' => $VacType]);
    }

    public function store(Request $request) {
        // $rules = array(
        //     'name' => 'unique:vac_types'
        // );
        // $params = $request->all();
        // $validator = Validator::make($params, $rules);
        //if ($validator->fails()) {
           // return response()->json(['success' => false, 'server_errors' => $validator->errors()]);
        //} else {
            $user_id = $this->request->user()->id;
            $VacType = new VacType();
            $VacType->fill($request->all());
            $VacType->user_id = $user_id;
            $VacType->save();
            return response()->json(['success' => true, 'data' => $VacType]);
        //}
    }

    public function update(Request $request, $id) {
        $rules = array(
            'name' => 'unique:vac_types,name,' . $id
        );
        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'server_errors' => $validator->errors()]);
        } else {
            $VacType = VacType::find($id);
            if ($VacType) {
                $VacType->update($request->all());
                return response()->json(['success' => true, 'data' => $VacType]);
            } else {
                return response()->json(['success' => false, 'data' => ""]);
            }
        }
    }
    
    // public function check_VacationType_exist(){
    //     $vacation_type=$_GET['vacation_type']; $vacation_type_id='0';
    //     if($vacation_type){
    //        $check_vacation_type=VacType::where([['name','=',$vacation_type],['is_delete','=','0']])->get(); 
    //        if($check_vacation_type){
    //         foreach ($check_vacation_type as $key => $value) {
    //                  $vacation_type_id=$value['id'];
                     
    //         }
    //         if($vacation_type_id!='0'){
    //             return response()->json(['success' => true, 'data' => $vacation_type_id]); 
    //         }else{
    //                 return response()->json(['success' => false, 'data' => '0']); 
    //         }
            
    //        }
    //     }
    // }

    public function get_user_vac_bal(){
        $id=$_POST['id'];  $type_id=$_POST['type']; 
        $check_user_exist=UsersVacations::where([['user_id','=',$id],['type','=',$type_id]])->orderBy('id', 'DESC')->first(); 
        if($check_user_exist){
              $remaining_leave=$check_user_exist['balance']-'1';
              if($remaining_leave =='0') {
                    return response()->json(['success' => true, 'data' => '0']);
              } else if ($remaining_leave > '0') {
                   $remaining_leave=$remaining_leave+1;
                return response()->json(['success' => true, 'data' => $remaining_leave]);
              } else if ($remaining_leave < '0'){
                return response()->json(['success' => true, 'data' => '0']);
              } 
        }else{
               $check_vac_limit=VacType::where([['id','=',$type_id],['is_delete','=','0']])->get(); 
               //print_r($check_vac_limit); die();
               if($check_vac_limit){
                  foreach ($check_vac_limit as $key => $value) {
                      $no_of_leave=$value['vac_limit'];
                       return response()->json(['success' => true, 'data' => $no_of_leave]);
                  }
                   
                  //echo  $check_vac_limit; die();
                 
               }
               
        }
    }
    public function get_user_vac_renew_date(Request $request){
        $user_id=$request['user_id'];
        if($user_id){
            $resume_date=UsersVacations::where('user_id','=',$user_id)->orderBy('id', 'DESC')->first();
            if($resume_date){
                $end_date=$resume_date['end_date'];
                $days_ago = date('Y/m/d', strtotime('-2 days', strtotime($end_date))); 
                $todays_date=date("Y/m/d"); 

                if($todays_date >= $days_ago ){
                       //echo "greater or equal";
                        return response()->json(['success' => true, 'data' => '1']); 
                }else{
                         //echo "smaller";
                        return response()->json(['success' => false, 'data' => '0']);
                }
            }
        }
    }
    public function update_user_vac_renew_date(Request $request){
         $end_date = $this->request->vs_end_date; 
         $id =$this->request->id; $ttl_lev='0';
         if(!empty($end_date) && !empty($id)){
            $user_info=UsersVacations::where('user_id','=',$id)->orderBy('id', 'DESC')->first();
           if($user_info){
                    $record_id=$user_info['id'];
                    $leave_id=$user_info['type']; 
                    $user_bal=$user_info['balance'];
                    $renew_dt=$user_info['end_date'];  
                               
            }
            if(!empty($leave_id) && !empty($renew_dt) &&  !empty($end_date) &&  !empty($user_bal) ){
                $total_leave_balance=VacType::where('id','=',$leave_id)->get(); 
                if($total_leave_balance){
                    foreach ($total_leave_balance as $key => $value) {
                           $ttl_lev=$value['vac_limit'];
                    }
                    
                    $datetime1 = new DateTime($renew_dt);
                    $datetime2 = new DateTime($end_date);
                    $interval = $datetime1->diff($datetime2);
                    $days_added=$interval->format('%d'); 
                    $balane_levs=$ttl_lev - $user_bal;
                    if($balane_levs){
                         $final_bal=$balane_levs-$days_added;   
                    }
                    // echo $final_bal; die();
                    if ($final_bal < 0)
                    {
                      $up_bal='0';
                    }else{
                           $up_bal=$final_bal;
                    }
                    $update_vac_end_date = UsersVacations::where('id',$record_id)
                                                                           ->update(['end_date' => $end_date,
                                                                                    'balance' => $up_bal
                                                                                   ]);
                                                                           
                    // echo $update_visa_end_date; die();
                    if($update_vac_end_date){
                            return response()->json(['success' => true, 'data' => $update_vac_end_date]); 
                        }else{
                            return response()->json(['success' => false, 'data' => '']);
                        }
                }
            }
            
            
        } 
    }
}


