<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\User;
use App\SalaryRelese;
use App\VacType;
use App\UsersAdditions;
use App\UsersDeductions;
use App\UsersVacations;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;


class SalaryRelController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function get_user_sal(){
        SalaryRelese::query()->update(array('is_delete' => '1'));
        $salary=0; $total_additions=0;$total_absences=0;$cut_vacation_amount=0;$balance;
        $id= $_POST['user_name']; 
        //echo $id; die();
        if($id){
            $users_data= User::where('name', '=', $id)
                            //    ->whereYear('created_at','<=', (new \Carbon\Carbon)->now()->year)
                            //    ->whereMonth('created_at','<=',(new \Carbon\Carbon)->now()->month)
                               ->first();
            //print_r($users_data);  die(); 
            $id= @$users_data->id;                 
            if(!empty($users_data->salary)){
                 
                $monthlysalary=(int)(@$users_data->salary/12);
                $name=@$users_data->name;
                $basic_salary=@$users_data->salary;
                $salary=$salary+$monthlysalary;
                //$id= $users_data[0]['id']
                // if it exist in additions
                $additions = UsersAdditions::where('user_id', '=',$id)
                                             ->whereYear('created_at', (new \Carbon\Carbon)->now()->year)
                                             ->whereMonth('created_at',(new \Carbon\Carbon)->now()->month)
                                             ->get(['id','amount']);
                if($additions){
                    foreach ($additions as $key1 => $value1) {
                                $total_additions=$total_additions + $value1['amount'];
                                $salary=($salary + $value1['amount']);
                               
                    }

                }

                // if it exist in deductions
                $deductions = UsersDeductions::where('user_id', '=',$id)
                                                     ->whereYear('created_at', (new \Carbon\Carbon)->now()->year)
                                                     ->whereMonth('created_at',(new \Carbon\Carbon)->now()->month)
                                                     ->get(['id','amount']);
                if($deductions){
                    foreach ($deductions as $key2 => $value2) {
                               $total_absences=$total_absences + $value2['amount'];
                               $salary=($salary - $value2['amount']);
                               
                    }

                }

                // if it exist in vacations
                $oneday_salary=(int)($monthlysalary/30 ); 
                $vacations = UsersVacations::where('user_id', '=',$id)
                                                     ->whereYear('created_at', (new \Carbon\Carbon)->now()->year)
                                                     ->whereMonth('created_at',(new \Carbon\Carbon)->now()->month)
                                                     ->get(['type' ,'start_date', 'end_date']);
                if($vacations){
                          
                    foreach ($vacations as $key3 => $value3) {
                                $vact_id=$value3['type'];
                                $leave_bal=$value3['balance'];
                                $start_date=Carbon::parse($value3['start_date']);
                                $end_date=Carbon::parse($value3['end_date']);
                                $type = VacType::where('id', '=',$vact_id)->get();
                                if($type[0]['is_payable']=='Yes' && $leave_bal=='0'){

                                    $vacation_days = $end_date->diffInDays($start_date); 
                                    $cut_vacation_amount=($oneday_salary * $vacation_days);
                                    $salary=($salary - $cut_vacation_amount);
                                                
                                }else{
                                        $vacation_days = $end_date->diffInDays($start_date); 
                                        $cut_vacation_amount=($oneday_salary * $vacation_days);
                                        $salary=($salary - $cut_vacation_amount);
                                                      
                                }
                               
                    }
                }
               
                $user_salary_details=array(
                                        'Emp_code'=>$id,
                                        'Emp_name'=>$name,
                                        'basic_salary'=>$basic_salary,
                                        'total_addition'=> $total_additions,
                                        'total_absence'=> $total_absences,
                                        "total_deducts" =>$cut_vacation_amount,
                                        "year" =>(new \Carbon\Carbon)->now()->year,
                                        "month" =>(new \Carbon\Carbon)->now()->month
                );
                
                $vac_bal=VacType::where('is_delete', '=','0')->get();
                if($vac_bal){
                    foreach ($vac_bal as $key4 => $value4) {
                             $vac_id=$value4['id'];
                             $name=$value4['name'];
                             $user_bal=UsersVacations::where([['user_id','=',$id],['type','=',$vac_id]])->orderBy('id', 'DESC')->first(); 
                            if(empty($user_bal)){
                                $balance='';
                            }else{
                                   $balance=$user_bal['balance'];
                            }
                            $vac_type=array(
                                        'type'=>$name,
                                        'balance'=>$balance,
                                        'emp_id'=>$id
                            );
                            $sal_rel_add= SalaryRelese::insert($vac_type); 
                    } 
                    
                }
                 $vac_type_res = SalaryRelese::where([['emp_id', '=',$id],['is_delete', '=','0']])->get();
                
                return response()->json(['success' => true, 'user_details' => $user_salary_details, 'vac_type_res' => $vac_type_res]);

            }else{
                return response()->json(['success' => false, 'user_details' =>'', 'msg' => "Please add salary" ]);
            }
        }else{
                return response()->json(['success' => false, 'user_details' =>'', 'msg' => "User not found"]);
        }
    }
}


