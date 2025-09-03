<?php

namespace App\Http\Controllers\API;

use App\Payroll;
use Carbon\Carbon;
use App\User;
use App\VacType;
use App\UsersAdditions;
use App\UsersDeductions;
use App\UsersVacations;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayrollController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function search_payroll_data(Request $request){
        $payroll='';
        Payroll::query()->update(array('is_delete' => '1'));
        @$data= @$request['data']; $salary=0; 
        if($data){
            $users_data= User::where([['is_delete', '=', '0'],['department','!=','1']])
                        ->whereYear('created_at','<=' ,$data['year'])
                        ->whereMonth('created_at','<=',$data['month'])
                        ->get();
            if($users_data){
               foreach ($users_data as $key => $value) {
                        if($value['salary']){
                            $monthlysalary=(int)($value['salary'] /12);
                            $id=$value['id'];
                            $civil_id=$value['civil_id'];
                            $dkey=$key.'.';
                            $salary=$salary+$monthlysalary;
                            //echo $salary; die();
                            // if it exist in additions
                            $additions = UsersAdditions::where('user_id', '=',$id)
                                                         ->whereYear('created_at', $data['year'])
                                                         ->whereMonth('created_at',$data['month'])
                                                         ->get(['id','amount']);
                            if($additions){
                                foreach ($additions as $key1 => $value1) {
                                           $salary=($salary + $value1['amount']);
                                   
                                }

                            }
                            // end of  additions
                            /*echo $salary; die();*/
                            // if it exist in deductions
                            $deductions = UsersDeductions::where('user_id', '=',$id)
                                                         ->whereYear('created_at', $data['year'])
                                                         ->whereMonth('created_at',$data['month'])
                                                         ->get(['id','amount']);
                            if($deductions){
                                foreach ($deductions as $key2 => $value2) {
                                    $salary=($salary - $value2['amount']);
                                   
                                }

                            }
                            // end of  deductions
                            // if it exist in vacations
                            //echo $id;
                            $oneday_salary=(int)($monthlysalary/30 ); 
                            $vacation = UsersVacations::where(['user_id' => $id])
                                                         ->whereYear('created_at', $data['year'])
                                                         ->whereMonth('created_at',$data['month'])
                                                         ->get(['type' ,'start_date', 'end_date','balance']);
                                                        
                            if($vacation){
                                foreach ($vacation as $key3 => $value3) {
                                          $vact_id=$value3['type'];
                                          $leave_bal=$value3['balance'];
                                          $start_date=Carbon::parse($value3['start_date']);
                                          $end_date=Carbon::parse($value3['end_date']);
                                          $type = VacType::where('id', '=',$vact_id)->get();
                                          if($type[0]['is_payable']=='Yes' && $leave_bal=='0'){

                                                $vacation_days = $end_date->diffInDays($start_date); 
                                                $cut_vacation_amount=($oneday_salary * $vacation_days);
                                                $salary=($salary - $cut_vacation_amount);
                                                    
                                          }

                                          if($type[0]['is_payable']=='No' && $leave_bal=='0'){
                                                
                                                $vacation_days = $end_date->diffInDays($start_date); 
                                                $cut_vacation_amount=($oneday_salary * $vacation_days);
                                                $salary=($salary - $cut_vacation_amount);
                                                
                                                            
                                          }
                                   
                                }
                            }  
                            // end of  vacations
                            $payroll=array(
                                            'year'=>$data['year'],
                                            'month'=>$data['month'],
                                            'emp_id'=>$id,
                                            'civil_id'=> $civil_id,
                                            'salary'=> $salary,
                                            "created_at" =>date('Y-m-d H:i:s'),
                                            "updated_at" =>date('Y-m-d H:i:s')
                            );
                           
                            $payroll_add= Payroll::insert($payroll); 
                            $salary='0';
                        }
                }  
                if($payroll){
                    return response()->json(['success' => true, 'data' => 'added Successfully','year'=>$data['year'],'month'=>$data['month'],'payroll'=>$payroll_add]); 
                }else{
                   return response()->json(['success' => true, 'data' => []]);
                }

            }else{
                   return response()->json(['success' => true, 'data' => []]);
            }
        } 
    }

    public function get_payroll_data(Request $request){

        $year= $request['year']; $month= $request['month'];
        $payroll_data = Payroll::where([['year','=' ,$year],['month','=',$month],['is_delete','=','0']])->get();
        if($payroll_data) {
          return response()->json(['success' => true, 'data' => $payroll_data]);  
        } 
    }

    

}


