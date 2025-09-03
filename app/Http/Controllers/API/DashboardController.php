<?php

namespace App\Http\Controllers\API;
use App\NewSpareParts;
use App\UsedSpareParts;
use App\CustomersNewSpareParts;
use App\CustomersUsedSpareParts;
use App\CabNo;
use App\JobCard;
use App\Customer; 
use App\Account; 


use App\JobCardsCalculation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function get_spare_parts_count_all() {
        // echo"1";exit;

            $new_spare = NewSpareParts::where([['is_delete', '=', 0],['balance', '!=', '0']])->count();
            $old_spare = UsedSpareParts::where([['is_delete', '=', 0],['balance', '!=', '0']])->count();
            // echo"<pre>";print_r($new_spare);
            // echo"<pre>";print_r($old_spare);exit;
            if($old_spare > 0 && $new_spare > 0){
                return response()->json(['success' => true, 'new' => $new_spare,'old' => $old_spare]);
            }else {
                return response()->json(['success' => false, 'data' => ""]);
            }

    }

    public function get_cust_count_all() {

        $Customer = Customer::where([['is_delete', '=', 0]])->count();
        if($Customer){
            return response()->json(['success' => true, 'Customer' => $Customer]);
        }else {
            return response()->json(['success' => false, 'data' => ""]);
        }

    }

    public function get_customeracc() {

        $Account = Account::where([['is_delete', '=', 0]])->count();
        if($Account){
            return response()->json(['success' => true, 'Account' => $Account]);
        }else {
            return response()->json(['success' => false, 'data' => ""]);
        }

    }
    

    public function get_jobs_count_all() {
            $jobs_paid_id=array(); $jobs_done=''; $jobs_assigned=''; $jobs_running=''; $job_payment_details=''; $jobs_total='';$jobs_cancelled='';
            $current=date("Y/m/d");
            $jobs_done=CabNo::whereDate('updated_at','=',$current)->where('job_status','=','delivery')->count();
            $jobs_assigned=CabNo::WhereDate('updated_at','=',$current)
                                      ->where(function($query) {
                                            $query->where('job_status', 'pending')
                                                ->orWhere('job_status', '=', 'under_test')
                                                ->orWhere('job_status', '=', 'working')
                                                ->orWhere('job_status', '=', 'delay')
                                                ->orWhere('job_status', '=', 'paint')
                                                ->orWhere('job_status', '=', 'print_req')
                                                ->orWhere('job_status', '=', 'paid_wait')
                                                ->orWhere('job_status', '=', 'clean_polish')
                                                ->orWhere('job_status', '=', 'on_change')
                                                ->orWhere('job_status', '=', 'cancel_req');
                                      })->count();
            $jobs_running=CabNo::WhereDate('updated_at','=',$current)
                                      ->where(function($query) {
                                            $query->where('job_status', 'pending')
                                                ->orWhere('job_status', '=', 'under_test')
                                                ->orWhere('job_status', '=', 'working')
                                                ->orWhere('job_status', '=', 'delay')
                                                ->orWhere('job_status', '=', 'paint')
                                                ->orWhere('job_status', '=', 'print_req')
                                                ->orWhere('job_status', '=', 'paid_wait')
                                                ->orWhere('job_status', '=', 'clean_polish')
                                                ->orWhere('job_status', '=', 'on_change');
                                      })->count();
            $jobs_paid_deatils=JobCard::WhereDate('updated_at','=',$current)
                                      ->where(function($query) {
                                            $query->where('status', 'delivery');
                                    })->get();
            if($jobs_paid_deatils){
              foreach ($jobs_paid_deatils as $key => $value) {
                  array_push($jobs_paid_id, $value['id']);
              }
            }

            $job_payment_details=JobCardsCalculation::where('balance','!=','0')->whereIn('job_id', $jobs_paid_id)->count();
            $jobs_total=CabNo::where([['job_id','!=','NULL'],['job_id','!=','0']])->count();
            $jobs_cancelled=CabNo::WhereDate('updated_at','=',$current)
                                      ->where(function($query) {
                                            $query->where('job_status', 'cancel_req');
                                    })->count();
            
                                    $Customer = $Account = $new_spare = $old_spare = 0;
            $Customer = Customer::where([['is_delete', '=', 0]])->count();
            $Account = Account::where([['is_delete', '=', 0]])->count();
            
            $new_spare = NewSpareParts::where([['is_delete', '=', 0],['balance', '!=', '0']])->count();
            $old_spare = UsedSpareParts::where([['is_delete', '=', 0],['balance', '!=', '0']])->count();
            

            return response()->json(['success' => true,'new'=>$new_spare,'old'=>$old_spare,'Customer' => $Customer,'Account' => $Account,'jobs_done' => $jobs_done,'jobs_assigned' => $jobs_assigned,'jobs_total'=>$jobs_total,'jobs_running'=>$jobs_running,'job_payment_details'=>$job_payment_details,'jobs_cancelled'=>$jobs_cancelled]);


    }

    public function get_newspare_count_count_all(){

        //$NewSpareParts1 = NewSpareParts::whereRaw('MONTH(created_at) = ?', (new \Carbon\Carbon)->now()->month )->get();;
        $NewSpareParts1 = NewSpareParts::whereMonth('created_at', (new \Carbon\Carbon)->now()->month )->count();
        $CustomersNewSpareParts1=CustomersNewSpareParts::whereMonth('created_at', (new \Carbon\Carbon)->now()->month )->count();
        $NewSpareParts2 = NewSpareParts::whereDate('created_at', (new \Carbon\Carbon)->now()->submonths(1)->month)->count();
        $CustomersNewSpareParts2=CustomersNewSpareParts::whereMonth('created_at',(new \Carbon\Carbon)->now()->submonths(1)->month)->count();
        $NewSpareParts3 = NewSpareParts::whereMonth('created_at', (new \Carbon\Carbon)->now()->submonths(2)->month)->count();
        $CustomersNewSpareParts3=CustomersNewSpareParts::whereMonth('created_at', (new \Carbon\Carbon)->now()->submonths(2)->month)->count();
        //if($NewSpareParts1 && $NewSpareParts2 && $NewSpareParts3 && $CustomersNewSpareParts1 && $CustomersNewSpareParts2 && $CustomersNewSpareParts3){
                        return response()->json(['success' => true, 'NewSpareParts1' => $NewSpareParts1,'NewSpareParts2' => $NewSpareParts2,'NewSpareParts3' => $NewSpareParts3,'CustomersNewSpareParts1' => $CustomersNewSpareParts1,'CustomersNewSpareParts2' => $CustomersNewSpareParts2,'CustomersNewSpareParts3' => $CustomersNewSpareParts3]);
        //}else {
                   // return response()->json(['success' => false, 'data' => ""]);
        //}
    }


    public function get_usedspare_count_count_all(){

        $UsedSpareParts1 = UsedSpareParts::whereMonth('created_at', (new \Carbon\Carbon)->now()->month)->get();
        $UsedSpareParts1 = $UsedSpareParts1->count();
        $CustomersUsedSpareParts1=CustomersUsedSpareParts::whereMonth('created_at', (new \Carbon\Carbon)->now()->month)->get();
        $CustomersUsedSpareParts1 = $CustomersUsedSpareParts1->count();
        $UsedSpareParts2 = UsedSpareParts::whereMonth('created_at', (new \Carbon\Carbon)->now()->submonths(1)->month  )->get();
        $UsedSpareParts2 = $UsedSpareParts2->count();
        $CustomersUsedSpareParts2=CustomersUsedSpareParts::whereMonth('created_at',(new \Carbon\Carbon)->now()->submonths(1)->month)->get();
        $CustomersUsedSpareParts2 = $CustomersUsedSpareParts2->count();
        $UsedSpareParts3 = UsedSpareParts::whereMonth('created_at', (new \Carbon\Carbon)->now()->submonths(2)->month )->get();
        $UsedSpareParts3 = $UsedSpareParts3->count();
        $CustomersUsedSpareParts3=CustomersUsedSpareParts::whereMonth('created_at', (new \Carbon\Carbon)->now()->submonths(2)->month)->get();
        $CustomersUsedSpareParts3 = $CustomersUsedSpareParts3->count();
        //if($UsedSpareParts1 && $UsedSpareParts2 && $UsedSpareParts3 && $CustomersUsedSpareParts1 && $CustomersUsedSpareParts2 && $CustomersUsedSpareParts3){
                        return response()->json(['success' => true, 'UsedSpareParts1' => $UsedSpareParts1,'UsedSpareParts2' => $UsedSpareParts2,'UsedSpareParts3' => $UsedSpareParts3,'CustomersUsedSpareParts1' => $CustomersUsedSpareParts1,'CustomersUsedSpareParts2' => $CustomersUsedSpareParts2,'CustomersUsedSpareParts3' => $CustomersUsedSpareParts3]);
       // }else {
                    //return response()->json(['success' => false, 'data' => ""]);
       // }
    }





}
