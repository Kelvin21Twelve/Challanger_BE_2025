<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\SendEmailController;
// All database declaration as use App\DB_Table;
use App\Account;
use App\Agency;
use App\Announcement;
use App\AttendancesEntries;
use App\Brand;
use App\CabNo;
use App\CarColor;
use App\CarMake;
use App\CarModel;
use App\Customer;
use App\CustomersLabour;
use App\CustomersNewSpareParts;
use App\CustomersUsedSpareParts;
use App\helper;
use App\Holyday;
use App\JobCard;
use App\JobCardPayment;
use App\JobCardsCalculation;
use App\JobTitle;
use App\Labour;
use App\Memo;
use App\Nationality;
use App\NewSpareParts;
use App\Permission;
use App\Role;
use App\RolesPermission;
use App\Supplier;
use App\UsedSpareParts;
use App\User;
use App\UsersAbsences;
use App\UsersAdditions;
use App\UsersAttendances;
use App\UsersDeductions;
use App\UsersDocuments;
use App\UsersExcuses;
use App\UsersRoles;
use App\UsersVacations;
use App\UsersWarnings;
use App\VacType;
use App\Vehicle;
use App\VisaType;
use App\NewSparePurchase;
use App\Expense;
use App\ExpenseType;
use App\SparePartsReturn;
use PDF;
use App\LabourServiceType;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function print_job_card(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();

        try {
            $job_id = $request->input("id");
            $job_card = JobCard::with("customer_details")->with("vehicle_details", "vehicle_details.view:id,make", "vehicle_details.type:id,model", "vehicle_details.color:id,color", "vehicle_details.agency:id,agency")->where("is_delete", 0)->where('id', $job_id)->first();
            $cab_no = CabNo::where('job_id', $job_id)->first();
            $job_info =  JobCardPayment::where('job_id', '=', $job_id)->get(['amount', 'pay_by', 'remaining']);

            $overdue = 0;
            if ($job_info) {
                foreach ($job_info as $key => $value2) {
                    if ($value2['pay_by'] == 2) {
                        $overdue = $overdue + $value2['amount'];
                    }
                }
            }
            if($cab_no!=''){
                $cab_no_no=$cab_no['cab_no'];
            }else{
                $cab_no_no='';
            }

            $main_array = array();
            if ($job_card) {
                $data_arr = array(
                    'id' => $job_card['id'],
                    'vehicle_id' => $job_card['vehicle'],
                    'job_no' => $job_card['job_no'],
                    'cab_no' => $cab_no_no,
                    'cust_name' => $job_card['customer_details']->cust_name,
                    'cust_name_id' => (string) $job_card['customer_details']->id,
                    'phone' => $job_card['customer_details']->phone,
                    'plate_no' => $job_card['vehicle_details']->plate_no,
                    'view' => $job_card['vehicle_details']['view']->make,
                    'type' => $job_card['vehicle_details']['type']->model,
                    'view_type' => $job_card['vehicle_details']['view']->make . " - " . $job_card['vehicle_details']['type']->model,
                    'model' => $job_card['vehicle_details']->model,
                    'color' => $job_card['vehicle_details']['color']->color,
                    'agency' => $job_card['vehicle_details']['agency']->agency,
                    'chasis' => $job_card['vehicle_details']['agency']->chasis_no,
                    'status' => $job_card['status'],
                    'entry_date' => $job_card['entry_date'],
                    'entry_time' => $job_card['entry_time'], // const win = new BrowserWindow({width: 800, height: 600});
                    'kilo_meters' => $job_card['kilo_meters'],
                    'approved' => $job_card['approved'],
                    'returned' => $job_card['returned'],
                    'warranty' => $job_card['warranty'],
                    'warranty_days' => $job_card['warranty_days'],
                    'delivery_date' => $job_card['delivery_date'],
                    'employee_responsible' => $job_card['employee_responsible'],
                    'notes' => $job_card['notes'],
                    'requested_parts' => $job_card['requested_parts'],
                    'lock_card' => $job_card['lock_card'],
                    'empty' => false
                );
                $main_array['job_card_details'] = $data_arr;
                $main_array['customer_used_spare_parts'] = CustomersUsedSpareParts::where('is_delete', 0)->where('job_id', $job_id)->get();
                $main_array['customer_new_spare_parts'] = CustomersNewSpareParts::where('is_delete', 0)->where('job_id', $job_id)->get();
                //$main_array['customer_labours'] = CustomersLabour::where('is_delete',0)->where('job_id',$job_id)->get();
                $labours_exist = CustomersLabour::where('is_delete', 0)->where('job_id', $job_id)->get();
                if ($labours_exist) {
                    foreach ($labours_exist as $key => $value) {
                        $labour_id = $value['labour_id'];
                        $labour_quantity = $value['quantity'];
                        if ($labour_id) {
                            $if_exist = Labour::find($labour_id);
                            if ($if_exist->service_type) {
                                $if_exist_id = LabourServiceType::find($if_exist->service_type);
                                $value['service_types'] = $if_exist_id->type;
                            }
                        } // labour id
                    }
                }
                $main_array['customer_labours'] = $labours_exist;
                //echo "<pre>"; print_r($main_array['customer_labours']); die();
            } // job card
            $main_array['job_card_calculation'] = JobCardsCalculation::where('job_id', $job_id)->first();
            $main_array['overdue'] = $overdue;
            $image = public_path('/image/challenger2-1.png');
            $main_array['image'] = $image;
            // print_r($main_array);die;
            $pdf  = PDF::loadView("report.job_card", compact('main_array'));
            $path = public_path('/');
            $fileName =  $user->id . time() . '.' . 'pdf';
            if (file_exists($path . '/' . $fileName)) {
                unlink($path . '/' . $fileName);
            }
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    public function print_customer_payment_old(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try{
            $job_id = $request->input("id");
            // echo"<pre>";print_r($job_id);
            $job_card = JobCard::with("customer_details")->where("is_delete", 0)->where('id', $job_id)->first();
            // echo"<pre>";print_r($job_card);
            // $cab_no = CabNo::where('job_id', $job_id)->first();
            $job_info =  JobCardPayment::where('job_id', '=', $job_id)->get(['amount', 'pay_by', 'remaining']);
            // echo"<pre>";print_r($job_info);exit;
            $main_array = array();
            if ($job_info) {
                foreach ($job_info as $key => $value2) {
                    if ($value2['pay_by'] == 1) {
                        $payBy = 'Cash';
                    }else if($value2['pay_by'] == 2){
                        $payBy = 'KNet';
                    }else if($value2['pay_by'] == 2){
                        $payBy = 'Visa';
                    }else{
                        $payBy = 'Master';
                    }
                    if ($job_card) {
                        $data_arr = array(
                            'id' => $job_card['id'],
                            'cust_name' => $job_card['customer'],
                            'cust_name_id' => (string) $job_card['customer_id'],
                            'phone' => $job_card['customer_details']->phone,
                            'amount' => $value2['amount'],
                            'pay_by' => $payBy,
                            'remaining' => $value2['remaining'],
                        );
                        $main_array[$key]=$data_arr;
                        $main_array[$key]=$data_arr;
                    }
                }
            }
            // echo "<pre>"; print_r($main_array);exit;
            $final_array['data']=$main_array;
            $image = public_path('/image/challenger2-1.png');
            $final_array['image'] = $image;
            $pdf  = PDF::loadView("report.print_customer_payment_report", compact('final_array'));
            // echo "<pre>"; print_r($pdf);die;

            $path = public_path('/');

            $fileName =  $user->id . time() . '.' . 'pdf';
            if (file_exists($path . '/' . $fileName)) {
                unlink($path . '/' . $fileName);
            }
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
            // echo "<pre>"; print_r($main_array);die;
        }catch(Exception $ex){
            return back()->withError($ex->getMessage())->withInput();

        }
    }
    public function print_customer_payment(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
       $user = Auth::user();

        try {
            $job_id = $request->input("id");
            $job_card = JobCard::with("customer_details")->where("is_delete", 0)->where('id', $job_id)->first();
            // echo"<pre>";print_r($job_card);
            // $cab_no = CabNo::where('job_id', $job_id)->first();
            $job_info =  JobCardPayment::where('job_id', '=', $job_id)->get(['amount', 'pay_by', 'remaining']);

            $overdue = 0;
            if ($job_info) {
                foreach ($job_info as $key => $value2) {
                    if ($value2['pay_by'] == 2) {
                        $overdue = $overdue + $value2['amount'];
                    }
                }
            }


            $main_array = array();
            if ($job_card) {
                $data_arr = array(
                    'id' => $job_card['id'],
                    'vehicle_id' => $job_card['vehicle'],
                    'job_no' => $job_card['job_no'],
                    'cab_no' => $job_card['cab_no'],
                    'cust_name' => $job_card['customer_details']->cust_name,
                    'cust_name_id' => (string) $job_card['customer_details']->id,
                    'phone' => $job_card['customer_details']->phone,
                    'plate_no' => $job_card['vehicle_details']->plate_no,
                    'view' => $job_card['vehicle_details']['view']->make,
                    'type' => $job_card['vehicle_details']['type']->model,
                    'view_type' => $job_card['vehicle_details']['view']->make . " - " . $job_card['vehicle_details']['type']->model,
                    'model' => $job_card['vehicle_details']->model,
                    'color' => $job_card['vehicle_details']['color']->color,
                    'agency' => $job_card['vehicle_details']['agency']->agency,
                    'chasis' => $job_card['vehicle_details']['agency']->chasis_no,
                    'status' => $job_card['status'],
                    'entry_date' => $job_card['entry_date'],
                    'entry_time' => $job_card['entry_time'], // const win = new BrowserWindow({width: 800, height: 600});
                    'kilo_meters' => $job_card['kilo_meters'],
                    'approved' => $job_card['approved'],
                    'returned' => $job_card['returned'],
                    'warranty' => $job_card['warranty'],
                    'warranty_days' => $job_card['warranty_days'],
                    'delivery_date' => $job_card['delivery_date'],
                    'employee_responsible' => $job_card['employee_responsible'],
                    'notes' => $job_card['notes'],
                    'requested_parts' => $job_card['requested_parts'],
                    'lock_card' => $job_card['lock_card'],
                    'empty' => false
                );
                $main_array['job_card_details'] = $data_arr;
                $main_array['customer_used_spare_parts'] = CustomersUsedSpareParts::where('is_delete', 0)->where('job_id', $job_id)->get();
                $main_array['customer_new_spare_parts'] = CustomersNewSpareParts::where('is_delete', 0)->where('job_id', $job_id)->get();
                //$main_array['customer_labours'] = CustomersLabour::where('is_delete',0)->where('job_id',$job_id)->get();
                $labours_exist = CustomersLabour::where('is_delete', 0)->where('job_id', $job_id)->get();
                if ($labours_exist) {
                    foreach ($labours_exist as $key => $value) {
                        $labour_id = $value['labour_id'];
                        $labour_quantity = $value['quantity'];
                        if ($labour_id) {
                            $if_exist = Labour::find($labour_id);
                            if ($if_exist->service_type) {
                                $if_exist_id = LabourServiceType::find($if_exist->service_type);
                                $value['service_types'] = $if_exist_id->type;
                            }
                        } // labour id
                    }
                }
                $main_array['customer_labours'] = $labours_exist;
                //echo "<pre>"; print_r($main_array['customer_labours']); die();
            } // job card
            $main_array['job_card_calculation'] = JobCardsCalculation::where('job_id', $job_id)->first();
            $main_array['overdue'] = $overdue;
            $image = public_path('/image/challenger2-1.png');
            $main_array['image'] = $image;
            // print_r($main_array);die;
            $pdf  = PDF::loadView("report.print_customer_payment_report", compact('main_array'));
            $path = public_path('/');
            $fileName =  $user->id . time() . '.' . 'pdf';
            if (file_exists($path . '/' . $fileName)) {
                unlink($path . '/' . $fileName);
            }
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    public function print_complete_jobcard(Request $request)
    {
        $user = Auth::user();

        try {
            $job_id = $request->input("id");
            $job_card = JobCard::with("customer_details")->with("vehicle_details", "vehicle_details.view:id,make", "vehicle_details.type:id,model", "vehicle_details.color:id,color", "vehicle_details.agency:id,agency")->where("is_delete", 0)->where('id', $job_id)->first();
            // $cab_no = CabNo::where('job_id', $job_id)->first();
            $job_info =  JobCardPayment::where('job_id', '=', $job_id)->get(['amount', 'pay_by', 'remaining']);

            $overdue = 0;
            if ($job_info) {
                foreach ($job_info as $key => $value2) {
                    if ($value2['pay_by'] == 2) {
                        $overdue = $overdue + $value2['amount'];
                    }
                }
            }


            $main_array = array();
            if ($job_card) {
                $data_arr = array(
                    'id' => $job_card['id'],
                    'vehicle_id' => $job_card['vehicle'],
                    'job_no' => $job_card['job_no'],
                    'cab_no' => $job_card['cab_no'],
                    'cust_name' => $job_card['customer_details']->cust_name,
                    'cust_name_id' => (string) $job_card['customer_details']->id,
                    'phone' => $job_card['customer_details']->phone,
                    'plate_no' => $job_card['vehicle_details']->plate_no,
                    'view' => $job_card['vehicle_details']['view']->make,
                    'type' => $job_card['vehicle_details']['type']->model,
                    'view_type' => $job_card['vehicle_details']['view']->make . " - " . $job_card['vehicle_details']['type']->model,
                    'model' => $job_card['vehicle_details']->model,
                    'color' => $job_card['vehicle_details']['color']->color,
                    'agency' => $job_card['vehicle_details']['agency']->agency,
                    'chasis' => $job_card['vehicle_details']['agency']->chasis_no,
                    'status' => $job_card['status'],
                    'entry_date' => $job_card['entry_date'],
                    'entry_time' => $job_card['entry_time'], // const win = new BrowserWindow({width: 800, height: 600});
                    'kilo_meters' => $job_card['kilo_meters'],
                    'approved' => $job_card['approved'],
                    'returned' => $job_card['returned'],
                    'warranty' => $job_card['warranty'],
                    'warranty_days' => $job_card['warranty_days'],
                    'delivery_date' => $job_card['delivery_date'],
                    'employee_responsible' => $job_card['employee_responsible'],
                    'notes' => $job_card['notes'],
                    'requested_parts' => $job_card['requested_parts'],
                    'lock_card' => $job_card['lock_card'],
                    'empty' => false
                );
                $main_array['job_card_details'] = $data_arr;
                $main_array['customer_used_spare_parts'] = CustomersUsedSpareParts::where('is_delete', 0)->where('job_id', $job_id)->get();
                $main_array['customer_new_spare_parts'] = CustomersNewSpareParts::where('is_delete', 0)->where('job_id', $job_id)->get();
                //$main_array['customer_labours'] = CustomersLabour::where('is_delete',0)->where('job_id',$job_id)->get();
                $labours_exist = CustomersLabour::where('is_delete', 0)->where('job_id', $job_id)->get();
                if ($labours_exist) {
                    foreach ($labours_exist as $key => $value) {
                        $labour_id = $value['labour_id'];
                        $labour_quantity = $value['quantity'];
                        if ($labour_id) {
                            $if_exist = Labour::find($labour_id);
                            if ($if_exist->service_type) {
                                $if_exist_id = LabourServiceType::find($if_exist->service_type);
                                $value['service_types'] = $if_exist_id->type;
                            }
                        } // labour id
                    }
                }
                $main_array['customer_labours'] = $labours_exist;
                //echo "<pre>"; print_r($main_array['customer_labours']); die();
            } // job card
            $main_array['job_card_calculation'] = JobCardsCalculation::where('job_id', $job_id)->first();
            $main_array['overdue'] = $overdue;
            $image = public_path('/image/challenger2-1.png');
            $main_array['image'] = $image;
            // print_r($main_array);die;
            $pdf  = PDF::loadView("report.complete_job_card", compact('main_array'));
            $path = public_path('/');
            $fileName =  $user->id . time() . '.' . 'pdf';
            if (file_exists($path . '/' . $fileName)) {
                unlink($path . '/' . $fileName);
            }
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    

    //===========================================================================
    //print_customer_details is done=========================================Done
    public function print_customer_details()
    {
        $user = Auth::user();
        try {

            $customers = Customer::with("nationality_name")->where(['is_delete' => 0])->get();
            $main_array = array();
            if ($customers) {
                foreach ($customers as $customer) {
                    $data_arr = array(
                        'user_id' => $customer->user_id,
                        'cust_name' => $customer->cust_name,
                        'civil_id' => $customer->civil_id,
                        'nationality' => $customer['nationality_name']->nationality,
                        'phone' => $customer->phone,
                        'mobile' => $customer->mobile,
                        'fax' => $customer->fax
                    );
                    $main_array[] = $data_arr;
                }
            }

            $view = view("report/print_customer_detail", compact('main_array'))->render();
            //PDF::setOptions(['dpi' => 150, 'defaultFont' => 'dejavu sans']);
            $pdf  = PDF::loadView("report.print_customer_detail", compact('main_array'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    //===========================================================================


    //===========================================================================Pending
    //selected options for job card
    public function print_all_job_card(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $status = '';
        $customer = '';
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            // $send_mail = $data['data']['send_mail'];

            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $total = $job_card['grand_total'] - ($job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total']);
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']]])->get();
                    if (!empty($customer_details)) {
                        foreach ($customer_details as $value) {
                            $status = $value['status'];
                            $customer = $value['customer'];
                        }
                        if ($status == 'pending' || $status == 'under_test' || $status == 'working' || $status == 'delay' || $status == 'paint' || $status == 'print_req' || $status == 'paid_wait' || $status == 'clean_polish' || $status == 'on_change' || $status == 'cancel_req') {
                            $posted = 'Unposted';
                        } else {
                            $posted = 'Posted';
                        }
                        $data_arr = array(
                            'job_card_no' => $job_card->job_id,
                            'customer' => $customer,
                            'service' => $total,
                            'used' => $job_card['used_spare_parts_total'],
                            'new' => $job_card['new_spare_parts_total'],
                            'total' => $job_card['grand_total'],
                            'is_posted' => $posted
                        );
                        $main_array[] = $data_arr;
                    }
                }
            }/*print_R($main_array); die();*/
            // if($send_mail==true)
            // {
            $view = view("report/print_all_job_card", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_all_job_card", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;

            // $email = "hruturaj@appristine.in";
            // $user_Name = "Hruturaj";
            // $subject = "Job cards subject";
            // sendMail($view, $email, $user_Name, $subject, $title = "");
            // }
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    // html Options1
    public function print_posted_card(Request $request)
    {
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $total = $job_card['grand_total'] - ($job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total']);
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']], ['status', '=', 'delivery']])->get();
                    /*print_r($customer_details); die();*/
                    if (!empty($customer_details)) {
                        foreach ($customer_details as $key => $customer_detail) {
                            $data_arr = array(
                                'job_card_no' => $job_card->job_id,
                                'customer' => $customer_detail['customer'],
                                'service' => $total,
                                'used' => $job_card['used_spare_parts_total'],
                                'new' => $job_card['new_spare_parts_total'],
                                'total' => $job_card['grand_total'],
                                'is_posted' => 'Posted'
                            );
                            $main_array[] = $data_arr;
                        }
                    }
                }
            }/*print_R($main_array); die();*/
            $view = view("report/print_posted_card", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_posted_card", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    // html Options2
    public function print_unposted_card(Request $request)
    {
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $total = $job_card['grand_total'] - ($job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total']);
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']]])
                        ->where(function ($query) {
                            $query->where('status', 'pending')
                                ->orWhere('status', '=', 'under_test')
                                ->orWhere('status', '=', 'working')
                                ->orWhere('status', '=', 'delay')
                                ->orWhere('status', '=', 'paint')
                                ->orWhere('status', '=', 'print_req')
                                ->orWhere('status', '=', 'paid_wait')
                                ->orWhere('status', '=', 'clean_polish')
                                ->orWhere('status', '=', 'on_change');
                        })->get();

                    /*print_r($customer_details); die();*/
                    if (!empty($customer_details)) {
                        foreach ($customer_details as $key => $customer_detail) {
                            $data_arr = array(
                                'job_card_no' => $job_card->job_id,
                                'customer' => $customer_detail['customer'],
                                'service' => $total,
                                'used' => $job_card['used_spare_parts_total'],
                                'new' => $job_card['new_spare_parts_total'],
                                'total' => $job_card['grand_total'],
                                'is_posted' => 'Unposted'
                            );
                            $main_array[] = $data_arr;
                        }
                    }
                }
            }/*print_R($main_array); die();*/
            $view = view("report/print_unposted_card", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_unposted_card", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    // html Options3
    public function print_canceled_card(Request $request)
    {
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $total = $job_card['grand_total'] - ($job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total']);
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']], ['status', '=', 'cancel_req']])->get();
                    /*print_r($customer_details); die();*/
                    if (!empty($customer_details)) {
                        foreach ($customer_details as $key => $customer_detail) {
                            $data_arr = array(
                                'job_card_no' => $job_card->job_id,
                                'customer' => $customer_detail['customer'],
                                'service' => $total,
                                'used' => $job_card['used_spare_parts_total'],
                                'new' => $job_card['new_spare_parts_total'],
                                'total' => $job_card['grand_total'],
                                'is_posted' => '-'
                            );
                            $main_array[] = $data_arr;
                        }
                    }
                }
            }/*print_R($main_array); die();*/
            $view = view("report/print_canceled_card", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_canceled_card", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    // html Options4
    public function print_labours_card(Request $request)
    {
        $status = '';
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->where('labours_total', '>', '0')->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']]])->get();
                    /*print_r($customer_details); die();*/
                    if (!empty($customer_details)) {
                        foreach ($customer_details as $value) {
                            $status = $value['status'];
                        }
                        if ($status == 'pending' || $status == 'under_test' || $status == 'working' || $status == 'delay' || $status == 'paint' || $status == 'print_req' || $status == 'paid_wait' || $status == 'clean_polish' || $status == 'on_change' || $status == 'cancel_req') {
                            $posted = 'Unposted';
                        } else {
                            $posted = 'Posted';
                        }
                        $labour_details = CustomersLabour::where([['is_delete', '=', '0'], ['job_id', '=', $job_card['job_id']]])->get();
                        /*print_r($customer_details); die();*/
                        if (!empty($labour_details)) {
                            foreach ($labour_details as $key => $labour_detail) {
                                $data_arr = array(
                                    'job_card_no' => $job_card->job_id,
                                    'labour_name' => $labour_detail['labour_name'],
                                    'quantity' => $labour_detail['quantity'],
                                    'total' => $job_card['labours_total'],
                                    'is_posted' => $posted
                                );
                                $main_array[] = $data_arr;
                            }
                        }
                    }
                }/*print_R($main_array); die();*/
            }
            $view = view("report/print_labours_card", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_labours_card", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    //===========================================================================


    //selected options for Spare part sale  is done==============================Pending
    public function print_all_spare_parts_sale(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        // echo "<pre>";print_r($request->all());exit;   
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            // echo "<pre>";print_r($job_cards);exit;
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $total = $job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total'] + $job_card['labours_total'];
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']]])->get();
                    if (!empty($customer_details)) {
                        foreach ($customer_details as $key => $customer_detail) {
                            if (!empty($customer_detail['status'])) {
                                if ($customer_detail['status'] == 'delivery') {
                                    $posted = 'Posted';
                                } else {
                                    $posted = 'Unposted';
                                }
                            } else {
                                $posted = 'Unposted';
                            }
                            $customer_used_spare_parts = CustomersUsedSpareParts::where('is_delete', 0)->where('job_id', $job_card['job_id'])->first();
                            if($customer_used_spare_parts!=''){
                                // print_r($customer_used_spare_parts); 
                                $customer_used_spare_parts=$customer_used_spare_parts;
                                $customer_used_spare_parts_item_name=$customer_used_spare_parts['item_name'];
                                $customer_used_spare_parts_qty=$customer_used_spare_parts['quantity'];
                                $customer_used_spare_parts_price=$customer_used_spare_parts['price'];
                                $customer_used_spare_parts_total=$customer_used_spare_parts['price']*$customer_used_spare_parts['quantity'];
                            }else{
                                // $customer_used_spare_parts='';
                                $customer_used_spare_parts_item_name='';
                                $customer_used_spare_parts_qty='';
                                $customer_used_spare_parts_price='';
                                $customer_used_spare_parts_total='';


                            }
                            $customer_new_spare_parts = CustomersNewSpareParts::where('is_delete', 0)->where('job_id', $job_card['job_id'])->first();
                            if($customer_new_spare_parts!=''){
                                // echo"<pre>";print_r($customer_new_spare_parts['item']); 
                                $customer_new_spare_parts=$customer_new_spare_parts;
                                
                                $customer_new_spare_parts_item_code=$customer_new_spare_parts['item_code'];
                                $customer_new_spare_parts_item=$customer_new_spare_parts['item'];
                                $customer_new_spare_parts_discount=$customer_new_spare_parts['discount'];
                                $customer_new_spare_parts_qty=$customer_new_spare_parts['quantity'];
                                $customer_new_spare_parts_price=$customer_new_spare_parts['price'];
                                $customer_new_spare_parts_total=$customer_new_spare_parts['total'];
                            }else{
                                // $customer_new_spare_parts='';
                                $customer_new_spare_parts_item_code='';
                                $customer_new_spare_parts_item='';
                                $customer_new_spare_parts_discount='';
                                $customer_new_spare_parts_qty='';
                                $customer_new_spare_parts_price='';
                                $customer_new_spare_parts_total='';
                            }
                            $data_arr = array(
                                'inv_no' => $job_card->id,
                                'inv_date' => $job_card->created_at,
                                'job_card_no' => $customer_detail['job_no'],
                                'name' => $customer_detail['customer'],
                                'total' => $total,
                                'is_posted' => $posted,
                                'customer_used_spare_parts_item_name' => $customer_used_spare_parts_item_name,
                                'customer_used_spare_parts_qty' => $customer_used_spare_parts_qty,
                                'customer_used_spare_parts_price' => $customer_used_spare_parts_price,
                                'customer_used_spare_parts_total'=>$customer_used_spare_parts_total,
                                'customer_new_spare_parts_item_code'=>$customer_new_spare_parts_item_code,
                                'customer_new_spare_parts_item'=>$customer_new_spare_parts_item,
                                'customer_new_spare_parts_discount'=>$customer_new_spare_parts_discount,
                                'customer_new_spare_parts_qty'=>$customer_new_spare_parts_qty,
                                'customer_new_spare_parts_price'=>$customer_new_spare_parts_price,
                                'customer_new_spare_parts_total'=>$customer_new_spare_parts_total,
                            );
                            // print_r($data_arr); die();
                            $main_array[] = $data_arr;
                        }
                    }
                }
            }
            // die();
            // print_R($main_array); die();
            $view = view("report/print_all_spare_parts_sale", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_all_spare_parts_sale", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    // html Options1
    public function print_with_job_card(Request $request)
    {
        // echo "<pre>";print_r($request->all());exit;
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->where([['used_spare_parts_total', '>', '0'], ['new_spare_parts_total', '>', '0']])->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $total = $job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total'] + $job_card['labours_total'];
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']]])->get();
                    /*print_r($customer_details); die();*/
                    if (!empty($customer_details)) {
                        foreach ($customer_details as $key => $customer_detail) {
                            if (!empty($customer_details['status']) == 'pending' || !empty($customer_details['status']) == 'under_test' || !empty($customer_details['status']) == 'working' || !empty($customer_details['status']) == 'delay' || !empty($customer_details['status']) == 'paint' || !empty($customer_details['status']) == 'print_req' || !empty($customer_details['status']) == 'paid_wait' || !empty($customer_details['status']) == 'clean_polish' || !empty($customer_details['status']) == 'on_change' || !empty($customer_details['status']) == 'cancel_req') {
                                $posted = 'Unposted';
                            } else {
                                $posted = 'Posted';
                            }

                            $customer_used_spare_parts = CustomersUsedSpareParts::where('is_delete', 0)->where('job_id', $job_card['job_id'])->first();
                            if($customer_used_spare_parts!=''){
                                // print_r($customer_used_spare_parts); 
                                $customer_used_spare_parts=$customer_used_spare_parts;
                                $customer_used_spare_parts_item_name=$customer_used_spare_parts['item_name'];
                                $customer_used_spare_parts_qty=$customer_used_spare_parts['quantity'];
                                $customer_used_spare_parts_price=$customer_used_spare_parts['price'];
                                $customer_used_spare_parts_total=$customer_used_spare_parts['price']*$customer_used_spare_parts['quantity'];
                            }else{
                                // $customer_used_spare_parts='';
                                $customer_used_spare_parts_item_name='';
                                $customer_used_spare_parts_qty='';
                                $customer_used_spare_parts_price='';
                                $customer_used_spare_parts_total='';


                            }
                            $customer_new_spare_parts = CustomersNewSpareParts::where('is_delete', 0)->where('job_id', $job_card['job_id'])->first();
                            if($customer_new_spare_parts!=''){
                                // echo"<pre>";print_r($customer_new_spare_parts['item']); 
                                $customer_new_spare_parts=$customer_new_spare_parts;
                                
                                $customer_new_spare_parts_item_code=$customer_new_spare_parts['item_code'];
                                $customer_new_spare_parts_item=$customer_new_spare_parts['item'];
                                $customer_new_spare_parts_discount=$customer_new_spare_parts['discount'];
                                $customer_new_spare_parts_qty=$customer_new_spare_parts['quantity'];
                                $customer_new_spare_parts_price=$customer_new_spare_parts['price'];
                                $customer_new_spare_parts_total=$customer_new_spare_parts['total'];
                            }else{
                                // $customer_new_spare_parts='';
                                $customer_new_spare_parts_item_code='';
                                $customer_new_spare_parts_item='';
                                $customer_new_spare_parts_discount='';
                                $customer_new_spare_parts_qty='';
                                $customer_new_spare_parts_price='';
                                $customer_new_spare_parts_total='';
                            }
                            $data_arr = array(
                                'inv_no' => $job_card->id,
                                'inv_date' => $job_card->created_at,
                                'job_card_no' => $customer_detail['job_no'],
                                'name' => $customer_detail['customer'],
                                'total' => $total,
                                'is_posted' => $posted,
                                'customer_used_spare_parts_item_name' => $customer_used_spare_parts_item_name,
                                'customer_used_spare_parts_qty' => $customer_used_spare_parts_qty,
                                'customer_used_spare_parts_price' => $customer_used_spare_parts_price,
                                'customer_used_spare_parts_total'=>$customer_used_spare_parts_total,
                                'customer_new_spare_parts_item_code'=>$customer_new_spare_parts_item_code,
                                'customer_new_spare_parts_item'=>$customer_new_spare_parts_item,
                                'customer_new_spare_parts_discount'=>$customer_new_spare_parts_discount,
                                'customer_new_spare_parts_qty'=>$customer_new_spare_parts_qty,
                                'customer_new_spare_parts_price'=>$customer_new_spare_parts_price,
                                'customer_new_spare_parts_total'=>$customer_new_spare_parts_total,
                            );
                            $main_array[] = $data_arr;
                        }
                    }
                }
            }/*print_R($main_array); die();*/
            $view = view("report/print_with_job_card", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_with_job_card", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    // html Options2
    public function print_without_job_card(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->where([['used_spare_parts_total', '=', '0'], ['new_spare_parts_total', '=', '0']])->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $total = $job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total'] + $job_card['labours_total'];
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']]])->get();
                    /*print_r($customer_details); die();*/
                    if (!empty($customer_details)) {

                        if (!empty($customer_details['status']) == 'pending' || !empty($customer_details['status']) == 'under_test' || !empty($customer_details['status']) == 'working' || !empty($customer_details['status']) == 'delay' || !empty($customer_details['status']) == 'paint' || !empty($customer_details['status']) == 'print_req' || !empty($customer_details['status']) == 'paid_wait' || !empty($customer_details['status']) == 'clean_polish' || !empty($customer_details['status']) == 'on_change' || !empty($customer_details['status']) == 'cancel_req') {
                            $posted = 'Unposted';
                        } else {
                            $posted = 'Posted';
                        }
                        foreach ($customer_details as $key => $customer_detail) {

                            $customer_used_spare_parts = CustomersUsedSpareParts::where('is_delete', 0)->where('job_id', $job_card['job_id'])->first();
                            if($customer_used_spare_parts!=''){
                                // print_r($customer_used_spare_parts); 
                                $customer_used_spare_parts=$customer_used_spare_parts;
                                $customer_used_spare_parts_item_name=$customer_used_spare_parts['item_name'];
                                $customer_used_spare_parts_qty=$customer_used_spare_parts['quantity'];
                                $customer_used_spare_parts_price=$customer_used_spare_parts['price'];
                                $customer_used_spare_parts_total=$customer_used_spare_parts['price']*$customer_used_spare_parts['quantity'];
                            }else{
                                // $customer_used_spare_parts='';
                                $customer_used_spare_parts_item_name='';
                                $customer_used_spare_parts_qty='';
                                $customer_used_spare_parts_price='';
                                $customer_used_spare_parts_total='';


                            }
                            $customer_new_spare_parts = CustomersNewSpareParts::where('is_delete', 0)->where('job_id', $job_card['job_id'])->first();
                            if($customer_new_spare_parts!=''){
                                // echo"<pre>";print_r($customer_new_spare_parts['item']); 
                                $customer_new_spare_parts=$customer_new_spare_parts;
                                
                                $customer_new_spare_parts_item_code=$customer_new_spare_parts['item_code'];
                                $customer_new_spare_parts_item=$customer_new_spare_parts['item'];
                                $customer_new_spare_parts_discount=$customer_new_spare_parts['discount'];
                                $customer_new_spare_parts_qty=$customer_new_spare_parts['quantity'];
                                $customer_new_spare_parts_price=$customer_new_spare_parts['price'];
                                $customer_new_spare_parts_total=$customer_new_spare_parts['total'];
                            }else{
                                // $customer_new_spare_parts='';
                                $customer_new_spare_parts_item_code='';
                                $customer_new_spare_parts_item='';
                                $customer_new_spare_parts_discount='';
                                $customer_new_spare_parts_qty='';
                                $customer_new_spare_parts_price='';
                                $customer_new_spare_parts_total='';
                            }
                            $data_arr = array(
                                'inv_no' => $job_card->id,
                                'inv_date' => $job_card->created_at,
                                'job_card_no' => $customer_detail['job_no'],
                                'name' => $customer_detail['customer'],
                                'total' => $total,
                                'is_posted' => $posted,
                                'customer_used_spare_parts_item_name' => $customer_used_spare_parts_item_name,
                                'customer_used_spare_parts_qty' => $customer_used_spare_parts_qty,
                                'customer_used_spare_parts_price' => $customer_used_spare_parts_price,
                                'customer_used_spare_parts_total'=>$customer_used_spare_parts_total,
                                'customer_new_spare_parts_item_code'=>$customer_new_spare_parts_item_code,
                                'customer_new_spare_parts_item'=>$customer_new_spare_parts_item,
                                'customer_new_spare_parts_discount'=>$customer_new_spare_parts_discount,
                                'customer_new_spare_parts_qty'=>$customer_new_spare_parts_qty,
                                'customer_new_spare_parts_price'=>$customer_new_spare_parts_price,
                                'customer_new_spare_parts_total'=>$customer_new_spare_parts_total,
                            );
                            $main_array[] = $data_arr;
                        }
                    }
                }
            }
            $view = view("report/print_without_job_card", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_without_job_card", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function print_daily_details(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCardPayment::where(["is_delete" => 0])->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            //echo "<pre>"; print_r($job_cards); die();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    //payment mode
                    if ($job_card->pay_by == '1') {
                        $paymnet_mode = 'CASH';
                    } else if ($job_card->pay_by == '2') {
                        $paymnet_mode = 'K-NET';
                    } else if ($job_card->pay_by == '3') {
                        $paymnet_mode = 'VISA';
                    } else {
                        $paymnet_mode = 'MASTER';
                    }
                    // employee
                    $user_name = User::where([["is_delete", '=', 0], ['id', '=', $job_card->user_id]])->first();
                    if (!empty(@$user_name)) {

                        $data_arr = array(
                            'payment_id' => $job_card->id,
                            'payment_date' => $job_card->created_at,
                            'Payment_type' => $paymnet_mode,
                            'amount' => $job_card->amount,
                            'wONo' => "-",
                            'employee' => $user_name->name
                        );
                        $main_array[] = $data_arr;
                    }
                }
            }
            //print_r($main_array);die;
            $view = view("report/print_daily_details", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_daily_details", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    // html Options2
    public function print_daily_summery(Request $request)
    {
        $user = Auth::user();
        try {
            // DailySummary::query()->update(array('is_delete' => '1'));
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $start_year = date('Y', strtotime($from_date));
            $end_year = date('Y', strtotime($to_date));
            $cash = '0';
            $knet = '0';
            for ($i = $start_year; $i <= $end_year; $i++) {
                $payment_deatils = JobCardPayment::where("is_delete", '=', 0)
                    ->whereYear('created_at', '=', $i)
                    ->get();

                if ($payment_deatils) {
                    foreach ($payment_deatils as $key => $payment_deatil) {
                        if ($payment_deatil['pay_by'] == '1') {

                            $cash = $cash + $payment_deatil->amount;
                        }
                        if ($payment_deatil['pay_by'] == '2') {
                            $knet = $knet + $payment_deatil->amount;
                        }
                        $data_arr = array(
                            'date' => $i,
                            'cash' => $cash,
                            'knet' => $knet,
                            'total' => $cash + $knet
                        );
                    }
                    $main_array[] = $data_arr;
                    // $payment_add= DailySummary::insert($data_arr);

                }
            }
            $view = view("report/print_daily_summery", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_daily_summery", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    //===========================================================================


    //===========================================================================Pending
    public function print_all_sp_part_purchase(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if (!empty($from_date && $to_date)) {

                $all_purchases = NewSparePurchase::where(["is_delete" => 0])->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
                //echo "<pre>"; print_r($all_purchases); die();
                $main_array = array();
                if ($all_purchases) {
                    foreach ($all_purchases as $all_purchase) {
                        if ($all_purchase->inv_type == '1') {
                            $inv_type = 'Type1';
                        } else {
                            $inv_type = 'Type2';
                        }
                        $data_arr = array(
                            'date' => $all_purchase->date,
                            'inv_no' => $all_purchase->inv_no,
                            'inv_type' => $inv_type,
                            'supplier_name' => $all_purchase->supplier_name,
                            'item_code' => $all_purchase->item_code,
                            'item_name' => $all_purchase->item_name,
                            'quantity' => $all_purchase->quantity,
                            'price' => $all_purchase->price
                        );
                        $main_array[] = $data_arr;
                    }
                }
            }
            $view = view("report/print_all_sp_part_purchase", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_all_sp_part_purchase", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    // html Options1
    public function print_post_sp_part_purchase(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCard::with("job_card_calculation")->where([['is_delete', '=', 0], ['status', '=', 'delivery']])->whereDate('entry_date', '>=', $from_date)->whereDate('entry_date', '<=', $to_date)->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $data_arr = array(
                        'job_card_no' => $job_card->job_no,
                        'customer' => $job_card->customer,
                        'service' => (isset($job_card['job_card_calculation']->labours_total)) ? $job_card['job_card_calculation']->labours_total : "-",
                        'used' => (isset($job_card['job_card_calculation']->used_spare_parts_total)) ? $job_card['job_card_calculation']->used_spare_parts_total : "-",
                        'new' => (isset($job_card['job_card_calculation']->new_spare_parts_total)) ? $job_card['job_card_calculation']->new_spare_parts_total : "-",
                        'total' => (isset($job_card['job_card_calculation']->grand_total)) ? $job_card['job_card_calculation']->grand_total : "-",
                        'is_posted' => " - "
                    );
                    $main_array[] = $data_arr;
                }
            }
            $view = view("report/print_post_sp_part_purchase", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_post_sp_part_purchase", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    // html Options2
    public function print_unpost_sp_part_purchase(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCard::with("job_card_calculation")->where([['is_delete', '=', 0], ['status', '!=', 'delivery']])->whereDate('entry_date', '>=', $from_date)->whereDate('entry_date', '<=', $to_date)->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $data_arr = array(
                        'job_card_no' => $job_card->job_no,
                        'customer' => $job_card->customer,
                        'service' => (isset($job_card['job_card_calculation']->labours_total)) ? $job_card['job_card_calculation']->labours_total : "-",
                        'used' => (isset($job_card['job_card_calculation']->used_spare_parts_total)) ? $job_card['job_card_calculation']->used_spare_parts_total : "-",
                        'new' => (isset($job_card['job_card_calculation']->new_spare_parts_total)) ? $job_card['job_card_calculation']->new_spare_parts_total : "-",
                        'total' => (isset($job_card['job_card_calculation']->grand_total)) ? $job_card['job_card_calculation']->grand_total : "-",
                        'is_posted' => " - "
                    );
                    $main_array[] = $data_arr;
                }
            }
            $view = view("report/print_unpost_sp_part_purchase", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_unpost_sp_part_purchase", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    //===========================================================================


    //===========================================================================Pending
    public function print_users_target_report(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $new_spare_parts = NewSpareParts::where('is_delete', '=', 0)->get();
            //print_r($new_spare_parts); die();
            $main_array = array();
            if ($new_spare_parts) {
                foreach ($new_spare_parts as $new_spare_part) {
                    $data_arr = array(
                        'item_code' => $new_spare_part->item_code,
                        'item_name' => $new_spare_part->item_name,
                        'balance' => $new_spare_part->balance,
                        'available' => $new_spare_part->available
                    );
                    //print_r($data_arr); die();
                    $main_array[] = $data_arr;
                }
            }
            $view = view("report/print_users_target_report", compact('main_array'))->render();
            $pdf  = PDF::loadView("report.print_users_target_report", compact('main_array'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    //===========================================================================


    //===========================================================================Pending
    public function print_end_of_day()
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $current = date("Y/m/d");
            $payment_details = JobCardPayment::whereDate('created_at', '=', $current)->where(['is_delete' => 0])->get();
            $main_array = array();
            $payment_type_arr = array("1" => "CASH", "2" => "K-NET", "3" => "VISA", "4" => "MASTER");
            if ($payment_details) {
                //print_r($payment_details); die();
                foreach ($payment_details as $payment_detail) {
                    $user_info = User::where([['is_delete', '=', 0], ['id', '=', $payment_detail->user_id]])->get();
                    if (@$user_info[0]['name']) {
                        //print_r($user_info); die();     
                        $data_arr = array(
                            'shift_date' => @$payment_detail['created_at'],
                            'user_name' => @$user_info[0]['name'],
                            'paymnet_type' => @$payment_type_arr[$payment_detail['pay_by']],
                            'amount' => @$payment_detail['amount']

                        );
                        $main_array[] = $data_arr;
                    }
                }
            }
            $view = view("report/print_end_of_day", compact('main_array'))->render();
            $pdf  = PDF::loadView("report.print_end_of_day", compact('main_array'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    //===========================================================================


    //===========================================================================Pending
    public function print_spare_parts_net_profit(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $data = $request->all();
            $salesQty = '';
            $salesTotal = '';
            $returnQty = '';
            $returnTotal = '';
            $netProfit = '0';
            $main_array = array();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $new_spare_parts = NewSparePurchase::where(['is_delete' => 0])->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            if ($new_spare_parts) {
                foreach ($new_spare_parts as $key => $new_spare_part) {
                    $item_code = $new_spare_part['item_code'];
                    $purchaseQty = $new_spare_part['purchase_qty'];
                    $purchaseTotal = $new_spare_part['total_amt'];
                    // sales
                    $sales_details = CustomersNewSpareParts::where([['item_code', '=', $item_code], ['is_delete', '=', '0']])->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get()->count();
                    if ($sales_details) {
                        $salesQty = $sales_details;
                        $salesTotal = $new_spare_part['sale_price'] * $salesQty;
                    }
                    // return
                    $return_details = SparePartsReturn::where('item_code', '=', $item_code)->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get()->count();
                    if ($return_details) {
                        $returnQty = $return_details;
                        $returnTotal = $new_spare_part['sale_price'] * $returnQty;
                    }
                    $netProfit = (int)$purchaseTotal - ((int)$salesTotal + (int)$returnTotal);
                    $data_arr = array(
                        'item_code' => $item_code,
                        'purchaseQty' => $purchaseQty,
                        'purchaseTotal' => $purchaseTotal,
                        'salesQty' => $salesQty,
                        'salesTotal' => $salesTotal,
                        'salesDiscount' => '-',
                        'returnQty' => $returnQty,
                        'returnTotal' => $returnTotal,
                        'returnDiscount' => '-',
                        'netProfit' => (int)$netProfit
                    );
                    $main_array[] = $data_arr;
                }
            }

            $view = view("report/print_spare_parts_net_profit", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_spare_parts_net_profit", compact('main_array', 'from_date',  'to_date'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    //===========================================================================
    //print inventory is done====================================================Done
    public function print_inventory(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $used_spare_parts = UsedSpareParts::where(['is_delete' => 0])->get();
            $new_spare_parts = NewSpareParts::where(['is_delete' => 0])->get();
            $main_array = array();
            if ($new_spare_parts) {
                foreach ($new_spare_parts as $new_spare_part) {
                    $data_arr = array(
                        'item_code' => $new_spare_part->item_code,
                        'item_name' => $new_spare_part->item_name,
                        'balance' => $new_spare_part->balance,
                        'available' => '-'
                    );
                    $main_array[] = $data_arr;
                }
            }
            if ($used_spare_parts) {
                foreach ($used_spare_parts as $used_spare_part) {
                    $data_arr = array(
                        'item_code' => $used_spare_part->item_code,
                        'item_name' => $used_spare_part->item_name,
                        'balance' =>   $used_spare_part->balance,
                        'available' => '-'
                    );
                    $main_array[] = $data_arr;
                }
            }
            $view = view("report/print_inventory", compact('main_array'))->render();
            $pdf  = PDF::loadView("report.print_inventory", compact('main_array'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function print_account_details_All(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        if ($data) {
            $account_code = $data['data']['data'][0]['account_code'];
            if ($account_code) {
                $account_data = Account::where('account_code', '=', $account_code)->get();
                $main_array = array();
                if ($account_data) {
                    foreach ($account_data as $account_dataa) {
                        $data_arr = array(
                            'account_name_en' => $account_dataa->account_name_en,
                            'account_name_ar' => $account_dataa->account_name_ar,
                            'account_code' =>    $account_dataa->account_code,
                        );
                    }

                    $main_array[] = $data_arr;
                    $view = view("report/print_account_details", compact('main_array'))->render();
                    $pdf  = PDF::loadView("report.print_account_details", compact('main_array'));
                    $path = public_path('/');
                    $fileName =  $user->id . '.' . 'pdf';
                    $pdf->save($path . '/' . $fileName);
                    $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
                    return $result;
                }
            }
        }
    }

    public function get_user_sal_detail(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        try {
            $data = $request->all();
            $user_details = $data['data']['user_details'];
            $vac_details = $data['data']['vac_type_res'];
            $user_details1[] = $user_details;
            $vac_type1[] = $vac_details;
            //$view = view("report/print_sal_rel_details", compact('user_details1','vac_type1'))->render();
            //print_r($view); die();
            $pdf  = PDF::loadView("report.print_sal_rel_details", compact('user_details1', 'vac_type1'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    // public function print_salary_slip(Request $request){

    // }

    public function print_payroll_data(Request $request)
    {
        $user = Auth::user();
        try {
            $data = $request->all();
            $new_data = $data['data'];
           // print_r($data); die();
            if ($new_data) {
                foreach ($new_data as $key => $val) {
                    $data_arr = array(
                        'civil_id' => $val['civil_id'],
                        'name' => '-',
                        'salary' => $val['salary'],
                        'year' => $val['year'],
                        'month' => $val['month'],
                    );
                    $main_array[] = $data_arr;
                }
            }
            $view = view("report/print_payroll_details", compact('main_array'))->render();
            $pdf  = PDF::loadView("report.print_payroll_details", compact('main_array'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
            // send_email($result);
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    //===========================================================================
    /**
     * Dev : Rohit
     * print_supplier_report 
     */

    public function print_supplier_report(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        DB::enableQueryLog();
        try {
            $data = $request->all();
            $supplier = $data['data']['supplier'];
            $period = (isset($data['data']['supplier_wise_report'])) ? $data['data']['supplier_wise_report'] : "";
            $all_purchases = NewSparePurchase::where(["is_delete" => 0])->where(["supplier_name" => $supplier]);
            $from_date = "";
            $to_date = "";
            $week = 1;
            $year = date("Y");
            $month = date("m");
            if ($period) {
                if ($period == "1") {
                    // while($week<=5)
                    // {
                    //     $weekDate =  explode(" - ",$this->getFirstandLastDate($year, $month, $week));
                    //     $from_date = $weekDate[0];
                    //     $to_date = $weekDate[1];
                    // }
                    $from_date = date("Y-m-d", strtotime('monday this week'));
                    $to_date = date("Y-m-d", strtotime('sunday this week'));
                } elseif ($period == "2") {
                    $from_date = date('Y-m-01');
                    $to_date = date('Y-m-t');
                } else {
                    $from_date = date('Y-01-01');
                    $to_date = date('Y-12-31');
                }

                $all_purchases->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date);
            }
            $all_purchases = $all_purchases->get();


            // and then you can get query log

            //print_r(DB::getQueryLog());

            $main_array = array();
            if ($all_purchases) {
                foreach ($all_purchases as $all_purchase) {
                    if ($all_purchase->inv_type == '1') {
                        $inv_type = 'Type1';
                    } else {
                        $inv_type = 'Type2';
                    }
                    $data_arr = array(
                        'date' => $all_purchase->date,
                        'inv_no' => $all_purchase->inv_no,
                        'inv_type' => $inv_type,
                        'supplier_name' => $all_purchase->supplier_name,
                        'item_code' => $all_purchase->item_code,
                        'item_name' => $all_purchase->item_name,
                        'quantity' => $all_purchase->purchase_qty,
                        'price' => $all_purchase->total_amt
                    );
                    $main_array[] = $data_arr;
                }
            }
            //print_r($main_array);die;
            //$view = view("report/print_all_sp_part_purchase", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_all_sp_part_purchase", compact('main_array', 'from_date',  'to_date'));
            //print_r($main_array);die;
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    /**
     * Dev : Rohit
     * print_expense_report 
     */

    public function print_expense_report(Request $request)
    {
        ini_set('max_execution_time', '300'); //300 seconds = 5 minutes
        ini_set('pcre.backtrack_limit', 10000000000);
        $user = Auth::user();
        DB::enableQueryLog();
        try {
            $data = $request->all();
            $period = (isset($data['data']['supplier_wise_report'])) ? $data['data']['supplier_wise_report'] : "";
            $all_purchases = Expense::where(["is_delete" => 0]);
            $from_date = "";
            $to_date = "";
            $week = 1;
            $year = date("Y");
            $month = date("m");
            if ($period) {
                if ($period == "1") {
                    // while($week<=5)
                    // {
                    //     $weekDate =  explode(" - ",$this->getFirstandLastDate($year, $month, $week));
                    //     $from_date = $weekDate[0];
                    //     $to_date = $weekDate[1];
                    // }
                    $from_date = date("Y-m-d", strtotime('monday this week'));
                    $to_date = date("Y-m-d", strtotime('sunday this week'));
                } elseif ($period == "2") {
                    $from_date = date('Y-m-01');
                    $to_date = date('Y-m-t');
                } else {
                    $from_date = date('Y-01-01');
                    $to_date = date('Y-12-31');
                }

                $all_purchases->whereDate('exp_date', '>=', $from_date)->whereDate('exp_date', '<=', $to_date);
            } else {
                $from_date = $data['data']['from_date'];
                $to_date = $data['data']['to_date'];
                $all_purchases->whereDate('exp_date', '>=', $from_date)->whereDate('exp_date', '<=', $to_date);
            }
            $all_purchases = $all_purchases->get();


            // and then you can get query log

            //print_r(DB::getQueryLog());

            $main_array = $job_array = array();
            if ($all_purchases) {
                $total_exp = 0;
                foreach ($all_purchases as $all_purchase) {
                    $getType = ExpenseType::find($all_purchase->expense_type);
                    $data_arr = array(
                        'date' => $all_purchase->exp_date,
                        'acc_name' => $all_purchase->user_account,
                        'acc_num' => $all_purchase->vendor,
                        'exp_type' => $getType->type,
                        'amount' => $all_purchase->amount,
                        'note' => $all_purchase->note
                    );
                    $main_array[] = $data_arr;
                    $total_exp = $total_exp + $all_purchase->amount;
                }
            }

            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();

            if ($job_cards) {
                $total_jobs_amount = 0;
                foreach ($job_cards as $job_card) {
                    $total = $job_card['grand_total'] - ($job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total']);
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']]])->get();
                    if (!empty($customer_details)) {
                        foreach ($customer_details as $value) {
                            $status = $value['status'];
                            $customer = $value['customer'];
                        }
                        if ($status == 'pending' || $status == 'under_test' || $status == 'working' || $status == 'delay' || $status == 'paint' || $status == 'print_req' || $status == 'paid_wait' || $status == 'clean_polish' || $status == 'on_change' || $status == 'cancel_req') {
                            $posted = 'Unposted';
                        } else {
                            $posted = 'Posted';
                        }
                        $data_arr = array(
                            'job_card_no' => $job_card->job_id,
                            'customer' => $customer,
                            'service' => $total,
                            'used' => $job_card['used_spare_parts_total'],
                            'new' => $job_card['new_spare_parts_total'],
                            'total' => $job_card['grand_total'],
                            'is_posted' => $posted
                        );

                        $total_jobs_amount = $total_jobs_amount + $job_card['grand_total'];

                        $job_array[] = $data_arr;
                    }
                }
            }
            //print_r($main_array);die;
            //$view = view("report/print_all_sp_part_purchase", compact('main_array', 'from_date',  'to_date'))->render();
            $pdf  = PDF::loadView("report.print_all_expense_report", compact('main_array', 'from_date',  'to_date', 'total_exp', 'job_array', 'total_jobs_amount'));
            //print_r($main_array);die;
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    function getFirstandLastDate($year, $month, $week)
    {

        $thisWeek = 1;

        for ($i = 1; $i < $week; $i++) {
            $thisWeek = $thisWeek + 7;
        }

        $currentDay = date('Y-m-d', mktime(0, 0, 0, $month, $thisWeek, $year));

        $monday = strtotime('monday this week', strtotime($currentDay));
        $sunday = strtotime('sunday this week', strtotime($currentDay));

        $weekStart = date('Y-m-d', $monday);
        $weekEnd = date('Y-m-d', $sunday);

        return $weekStart . ' - ' . $weekEnd;
    }
    /**
     * END
     */

    //print_customer_details is done=========================================Done
    public function print_invoice_details()
    {
        $inv_no = $_REQUEST['id'];
        $user = Auth::user();
        try {

            $invDetails = DB::table('new_spare_purchase')->where('inv_no', $inv_no)->get();
            //  dd($invDetails);
            // print_r($invDetails);exit;

            $main_array = array();
            if ($invDetails) {
                foreach ($invDetails as $item) {
                    $item->inv_no= $inv_no;
                    // $data_arr = array(
                    //     'id'   => $item->id,
                    //     'item_code' => $item->item_code,
                    //     'item_name' => $item->item_name,
                    //     'supplier_name' => $item->supplier_name,
                    //     'purchase_qty' => $item->purchase_qty,
                    //     'total_amt' => $item->total_amt,
                    //     'date' => $item->date,
                    // );
                }
                $main_array['data'] = $invDetails;
            }
            // print_r($main_array);exit;
            //  $image=public_path('image/challenger2-1.png');
            $image = public_path('/image/challenger2-1.png');
            $main_array['image'] = $image;
            //  $data=[
            //     'main_array'=>$main_array,
            //     'image'=>$image
            //  ];
            //              $view = view("report/print_invoice_details", compact('data'))->render();
            $view = view("report/print_invoice_details", compact('main_array'))->render();
            //PDF::setOptions(['dpi' => 150, 'defaultFont' => 'dejavu sans']);
            $pdf  = PDF::loadView("report.print_invoice_details", compact('main_array'));
            $path = public_path('/');
            $fileName =  $user->id . '.' . 'pdf';
            $pdf->save($path . '/' . $fileName);
            $result = response()->json(['success' => true, 'view' => url('/') . "/" . $fileName]);
            return $result;
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


    //===========================================================================
}
