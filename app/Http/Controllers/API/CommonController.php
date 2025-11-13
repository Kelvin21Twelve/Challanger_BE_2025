<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CabNo;
use App\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\CarModel;
use App\JobCard;
use App\NewSpareParts;
use App\Expense;
use App\JobCardsCalculation;


class CommonController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function delete(Request $request, $id) {

        $user = Auth::user();
        $user_id = $this->request->user()->id;
        $model_name = $request->input('model');
        $is_delete = false;
        switch (true) {
            case ($model_name == "CarMake" && $user->can('make-delete')):
                CarModel::where('make', '=', $id)->update(['is_delete' => 1]);
                $is_delete = true;
                break;
            case ($model_name == "CarModel" && $user->can('model-delete')):
                $is_delete = true;
                break;
            case ($model_name == "CarColor" && $user->can('color-delete')):
                $is_delete = true;
                break;
            case ($model_name == "Nationality" && $user->can('nationality-delete')):
                $is_delete = true;
                break;
            case ($model_name == "VisaType" && $user->can('visa-type-delete')):
                $is_delete = true;
                break;
            case ($model_name == "VacType" && $user->can('vacation-type-delete')):
                $is_delete = true;
                break;
            case ($model_name == "Labour" && $user->can('labour-delete')):
                $is_delete = true;
                break;
            case ($model_name == "Supplier" && $user->can('supplier-delete')):
                $is_delete = true;
                break;
            case ($model_name == "Agency" && $user->can('agency-delete')):
                $is_delete = true;
                break;
            case ($model_name == "JobTitle" && $user->can('job-title-delete')):
                $is_delete = true;
                break;
            case ($model_name == "User" && $user->can('system-user-delete')):
                $is_delete = true;
                break;
            case ($model_name == "Permission" && $user->can('department-delete')):
                $is_delete = true;
                break;
            case ($model_name == "Customer" && $user->can('customer-delete')):
                $is_delete = true;
                break;
            case ($model_name == "UsedSpareParts" && $user->can('used-spare-parts-delete')):
                $is_delete = true;
                break;
            case ($model_name == "NewSpareParts" && $user->can('new-spare-parts-delete')):
                $is_delete = true;
                break;
            case ($model_name == "Vehicle" && $user->can('car-delete')):
                $is_delete = true;
                break;
            case ($model_name == "CustomersNewSpareParts" && $user->can('spare-parts-sales-form-delete')):
                $is_delete = true;
                break;
            case ($model_name == "JobCard" && $user->can('job-card-delete')):
                $is_delete = true;
                break;
            case ($model_name == "CustomersUsedSpareParts" && $user->can('customers-used-spare-parts-delete')):
                $is_delete = true;
                break;
            case ($model_name == "CustomersNewSpareParts" && $user->can('customers-new-spare-parts-delete')):
                $is_delete = true;
                break;
            case ($model_name == "CarEngine" && $user->can('engine-delete')):
                    $is_delete = true;
                    break;    
            case ($model_name == "CustomersLabour" && $user->can('customers-labour-delete')):
                $is_delete = true;
                break;
            case ($model_name == "Expense" && $user->can('expense-delete')):
                $is_delete = true;
                break;
            case ($model_name == "ExpenseType" && $user->can('expense-type-delete')):
                $is_delete = true;
                break;
            default :
                $is_delete = false;
        }

        if ($model_name == "ExpenseType" || $model_name == "Expense" || $model_name == "UsersDocuments" || $model_name == "UsersAdditions" || $model_name == "UsersDeductions" || $model_name == "UsersVacations" || $model_name == "UsersExcuses" || $model_name == "UsersAbsences" || $model_name == "UsersWarnings" || $model_name == "UsersDocuments" || $model_name == "Memo" || $model_name == "Brand" || $model_name == "Announcement" || $model_name == "Holyday" || $model_name == "Account" || $model_name == "GeneralLedger" || $model_name == "CarEngine" || $model_name == "LabourServiceType"|| $model_name == "InvoiceType") {
            $is_delete = true;
        }
        
        if ($is_delete) {

            $model_all = '\\App\\' . $model_name;
           
            $model = new $model_all;
            $dynamic_model = $model::find($id);

            if ($dynamic_model) {
                //  echo 'Herer';
                // echo '<pre>';
                // print_r($dynamic_model);
                //echo $dynamic_model->job_id;exit;
               //  if(isset($dynamic_model->job_id) && ($model_name == "CustomersUsedSpareParts" || $model_name == "CustomersNewSpareParts" || $model_name == "CustomersLabour"))
               //  {
               //      $job_card_calculation =  JobCardsCalculation::where('job_id', '=', $dynamic_model->job_id)->get(['grand_total', 'balance', 'labour_disc']);

               //      if($job_card_calculation)
               //      {
               //          $balance = $job_card_calculation[0]['balance'];
               //          $total = $dynamic_model->quantity * $dynamic_model->price;
               //          if($balance < $total)
               //          {

               //              return response()->json(['success' => true, 'used_part_calc' => [], "status_calc" => 'no_update']);
               //          }
               //      }
               //  }
               // // exit;
                $dynamic_model->is_delete = 1;
                $dynamic_model->save();
                if ($model_name == "CustomersUsedSpareParts") {
                    $total = $dynamic_model->quantity * $dynamic_model->price;
                    $main_arr = app('App\Http\Controllers\API\CustUsedSparePartsController')->CalculationUsedSpareParts($user_id, $dynamic_model->job_id, $total, "delete", 0);
                    return response()->json(['success' => true, 'used_part_calc' => $main_arr['used_part_calc'], "status_calc" => $main_arr['status_calc']]);
                }
                if ($model_name == "CustomersNewSpareParts") {
                    $total = $dynamic_model->quantity * $dynamic_model->price;
                    $update_bal=NewSpareParts::find($dynamic_model->item_id);
                    if(!empty($update_bal)){
                        $bal=$update_bal->balance + $dynamic_model->quantity;
                        NewSpareParts::where('id',$dynamic_model->item_id)->update(['balance'=> $bal]);
                    }else{
                            // echo "spare part not found";
                    }
                    $main_arr = app('App\Http\Controllers\API\CustNewSparePartsController')->CalculationCustNewSpareParts($user_id, $dynamic_model->job_id, $total, "delete");
                    return response()->json(['success' => true, 'used_part_calc' => $main_arr['used_part_calc'], "status_calc" => $main_arr['status_calc']]);
                }
                if ($model_name == "CustomersLabour") {
                    $total = $dynamic_model->quantity * $dynamic_model->price;
                    $main_arr = app('App\Http\Controllers\API\CustsLaboursController')->CalculationLabours($user_id, $dynamic_model->job_id, $total, "delete", 0);
                    return response()->json(['success' => true, 'used_part_calc' => $main_arr['used_part_calc'], "status_calc" => $main_arr['status_calc']]);
                }
                if($model_name == "ExpenseType"){
                    $check_count=Expense::where("expense_type",$id)->count();
                    if($check_count){
                        $dynamic_model->is_delete = 0;
                        $dynamic_model->save();
                        return response('Unauthenticated.', 401);
                    }
                }
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } else {
            return response('Unauthenticated.', 401);
        }
    }

    public function syncDB(Request $request) {
        try {
            //$updated_at = $request->input('updated_at');
            $updated_at = "2019-01-01 00:00:00";
            $model_name = '\\App\\' . $request->input('model');
            $model = new $model_name;
            if ($updated_at) {
               $dynamic_model = $model::where([['updated_at', '>', $updated_at],['is_delete', '=', 0]])->get();
            } else {
                $dynamic_model = $model::where('is_delete', '=', 0)->all();
            }
            $results = $model::latest('updated_at')->first();
            if ($results) {
                $last_updated_at = (string) $results->updated_at;
            } else {
                $last_updated_at = "";
            }
            return array('data' => $dynamic_model, 'last_updated_at' => $last_updated_at);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function cabNo() {
        for ($i = 1; $i <= 200; $i++) {
            $is_cab = CabNo::where(['cab_no' => $i])->first();
            if ($is_cab) {

            } else {
                $model = new CabNo();
                $model->cab_no = $i;
                $model->save();
            }
        }
    }

    public function get_cabs(Request $request) {
        $status = $request->input("status");
        if ($status == "") {
            return $cabs = CabNo::where(['job_id' => 0])->orderBy("cab_no", "ASC")->get();
        } else {
            return $cabs = CabNo::where('job_id', "!=", 0)->orderBy("cab_no", "ASC")->get();
        }

    }

    public function search_customer(Request $request) {
        try {
            $customer_search = $request->input("customer_search");
            $phone_search = $request->input("phone_search");
            $plate_no_search = $request->input("plate_no_search");
            $cust_id = $request->input("cust_id");
            $query = DB::table('customers');
            $cases = array();
            (isset($customer_search) && $customer_search !== '') ? $cases[] = "customer_search" : '';
            (isset($phone_search) && $phone_search !== '') ? $cases[] = "phone_search" : '';
            (isset($plate_no_search) && $plate_no_search !== '') ? $cases[] = "plate_no_search" : '';
            (isset($cust_id) && $cust_id !== '') ? $cases[] = "cust_id" : '';

            foreach ($cases as $case) {
                switch ($case) {
                    case "customer_search":
                        $query->where('id', '=', $customer_search);
                        break;
                    case "phone_search":
                        $query->where('phone', 'like', "%{$phone_search}%");
                        break;
                    case "plate_no_search":
                        $vehicle = Vehicle::where('plate_no', 'like', "%{$plate_no_search}%")->select("customer")->get();
                        $custs = array();
                        if (!$vehicle->isEmpty()) {
                            foreach ($vehicle as $value) {
                                $custs[] = $value['customer'];
                            }
                        }
                        $query->whereIn('id', $custs);
                        break;
                   case "cust_id":
                            $query->where('id', '=', $cust_id);
                            $all_vehicle = Vehicle::where('customer','=',$cust_id)->get();
                            // $custs = array();
                            // if (!$vehicle->isEmpty()) {
                            //     foreach ($vehicle as $value) {
                            //         $custs[] = $value['customer'];
                            //     }
                            // }
                            //print_r($all_vehicle); die();
                            break;
                }
            }
            $result_new_ids = $query->select("*")->where('is_delete', '=', 0)->get();
            $result_new_ids_arr = array();
            foreach ($result_new_ids as $value) {
                $result_new_ids_arr[] = $value->id;
            }
            $result = $query->where('is_delete', '=', 0)->first();
            if ($result) {
                $customer_id = $result->id;
                $all_vehicle = Vehicle::with(['view'])->with(['type'])->with(['color'])->with(['engine'])->with(['agency'])->where(['customer' => $customer_id])->where('is_delete','0')->get();
            } else {
                $all_vehicle = "";
            }
            return response()->json(['success' => true, 'result_new_ids_arr' => implode(",", $result_new_ids_arr), 'customer' => ($result) ? (array) ($result) : "", "vehicle" => $all_vehicle]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function transfer_cab(Request $request) {
        try {
            $from_cab = $request->input("from_cab");
            $to_cab = $request->input("to_cab");
            $cab_nos = CabNo::where(['cab_no' => $from_cab])->first();
            if ($cab_nos) {
                $job_id = $cab_nos->job_id;
                $job_status = $cab_nos->job_status;
                CabNo::where(['cab_no' => $to_cab])->update(["job_id" => $job_id, "job_status" => $job_status]);
                $cab_nos->job_id = 0;
                $cab_nos->job_status = NULL;
                $cab_nos->save();
                JobCard::where(['id' => $job_id])->update(["cab_no" => $to_cab]);
                return response()->json(['success' => true, 'job_id' => $job_id, "cab_no" => $to_cab]);
            }
        } catch (Exception $ex) {
            return $e->getMessage();
        }
    }

    public function lock_unlock_jobcard(Request $request) {
        try {
            $status = $request->input("status");
            $job_id = $request->input("job_id");
            $job_card = JobCard::find($job_id);
            if ($job_card) {
                $job_card->lock_card = $status;
                $job_card->save();
                return response()->json(['success' => true, 'job_id' => $job_id, "lock_card" => $status]);
            }
        } catch (Exception $ex) {
            return $e->getMessage();
        }
    }

    public function collection_table() {
        $result = DB::select('SHOW TABLES');
        foreach ($result as $table) {
            DB::statement("ALTER TABLE " . $table->Tables_in_challenger . " CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        }
        echo "Done";
    }

}
