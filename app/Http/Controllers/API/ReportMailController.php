<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\SendEmailController;

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
use App\SparePartsReturn;
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
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerMail;
use App\Mail\PrintAllJobMail;
use App\Mail\PrintPostedJobMail;
use App\Mail\PrintUnpostedJobMail;
use App\Mail\PrintLaboursJobMail;
use App\Mail\PrintCancelledJobMail;
use App\Mail\AllSpareSales;
use App\Mail\PostedSpareSpart;
use App\Mail\UnpostedSpareSpart;
use App\Mail\DailyPayment;
use App\Mail\DailySummery;
use App\NewSparePurchase;
use App\Mail\AllSparePurchase;
use App\Mail\UserTargetReport;
use App\Mail\EndTargetReport;
use App\Mail\SparePartNetProfit;
use App\Mail\Inventory;
use App\Mail\PostedSpareParts;
use App\Mail\VisaRenew;
use App\Mail\VacationRenew;
use App\Mail\POSTSparePurchase;
use App\Mail\SupplierSpearPartsMail;
use Illuminate\Support\Facades\DB;

class ReportMailController extends Controller
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function mail_customer_details(Request $request)
    {
         $to = '';
        try {
             $data = $request->all();
             if(isset($data['data'])){
              $to = $data['data'];
               }

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

            if(!empty($to)){
               Mail::to($to)->send(new CustomerMail($main_array));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    //selected options for job card
    public function mail_all_job_cards(Request $request)
    {
        $status=''; $customer='';$send_to='';

        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

            $job_cards = JobCardsCalculation::whereBetween('created_at', [$from_date, $to_date])->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $total = $job_card['grand_total'] - ($job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total']);
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']]])->get();
                    if (!empty($customer_details)) {
                        foreach ($customer_details as $value) {
                              $status=$value['status'];
                              $customer=$value['customer'];
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
                            'is_posted' => $posted,
                        );
                        $main_array[] = $data_arr;
                    }
                }
            }

            if(!empty($send_to)){
               Mail::to($send_to)->send(new PrintAllJobMail($main_array, $from_date, $to_date));
            }

       } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


    public function mail_posted_job_card(Request $request)
    {
        $send_to='';
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

            $job_cards = JobCard::with("job_card_calculation")->where(['is_delete' => 0])->whereDate('entry_date', '>=', $from_date)->whereDate('entry_date', '<=', $to_date)->get();
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

            if(!empty($send_to)){
                 Mail::to($send_to)->send(new PrintPostedJobMail($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


    public function mail_unposted_job_card(Request $request)
    {
         $send_to='';
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

            $job_cards = JobCard::with("job_card_calculation")->where(['is_delete' => 0])->whereDate('entry_date', '>=', $from_date)->whereDate('entry_date', '<=', $to_date)->get();
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
            if(!empty($send_to)){
                   Mail::to($send_to)->send(new PrintUnpostedJobMail($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


    public function mail_canceled_job_card(Request $request)
    {
        $send_to='';
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

            $job_cards = JobCard::with("job_card_calculation")->where(['is_delete' => 0])->whereDate('entry_date', '>=', $from_date)->whereDate('entry_date', '<=', $to_date)->get();
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
            if(!empty($send_to)){
                  Mail::to($send_to)->send(new PrintCancelledJobMail($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


    public function mail_labours_job_card(Request $request)
    {
        $send_to='';
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

            $job_cards = JobCard::with("job_card_calculation")->where(['is_delete' => 0])->whereDate('entry_date', '>=', $from_date)->whereDate('entry_date', '<=', $to_date)->get();
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
            if(!empty($send_to)){
                  Mail::to($send_to)->send(new PrintLaboursJobMail($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function all_spare_parts_sales(Request $request)
    {
        $send_to='';
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $total = $job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total']+ $job_card['labours_total'];
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']]])->get();

                    if (!empty($customer_details)) {
                        if (!empty($customer_details['status']) == 'pending' || !empty($customer_details['status']) == 'under_test' || !empty($customer_details['status']) == 'working' || !empty($customer_details['status']) == 'delay' || !empty($customer_details['status']) == 'paint' || !empty($customer_details['status']) == 'print_req' || !empty($customer_details['status']) == 'paid_wait' || !empty($customer_details['status']) == 'clean_polish' || !empty($customer_details['status']) == 'on_change' || !empty($customer_details['status']) == 'cancel_req') {
                            $posted = 'Unposted';
                        } else {
                            $posted = 'Posted';
                        }
                        foreach ($customer_details as $key => $customer_detail) {

                            $data_arr = array(
                                'inv_no' => $job_card->id,
                                'inv_date' => $job_card->created_at,
                                'job_card_no' => $customer_detail['job_no'],
                                'name' => $customer_detail['customer'],
                                'total' => $total,
                                'is_posted' => $posted,
                            );
                            $main_array[] = $data_arr;
                        }
                    }
                }
            }
            if(!empty($send_to)){
                  Mail::to($send_to)->send(new AllSpareSales($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


    public function posted_spare_parts_sales(Request $request){
        $send_to='';
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->where([['used_spare_parts_total','>','0'],['new_spare_parts_total','>','0']])->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                   $total=$job_card['new_spare_parts_total']+ $job_card['used_spare_parts_total']+ $job_card['labours_total'];
                   $customer_details = JobCard::where([['is_delete' ,'=', '0'],['id','=',$job_card['job_id']]])->get();
                    /*print_r($customer_details); die();*/
                    if(!empty($customer_details)){

                        if(!empty($customer_details['status'])=='pending' || !empty($customer_details['status'])=='under_test' || !empty($customer_details['status'])=='working' || !empty($customer_details['status'])=='delay' || !empty($customer_details['status'])=='paint' || !empty($customer_details['status'])=='print_req' || !empty($customer_details['status'])=='paid_wait' || !empty($customer_details['status'])=='clean_polish' || !empty($customer_details['status'])=='on_change' || !empty($customer_details['status'])=='cancel_req' ){
                             $posted='Unposted';
                        }else{
                                $posted='Posted';
                        }
                        foreach ($customer_details as $key => $customer_detail) {

                            $data_arr = array(
                                'inv_no' => $job_card->id,
                                'inv_date' => $job_card->created_at,
                                'job_card_no' =>$customer_detail['job_no'],
                                'name' => $customer_detail['customer'],
                                'total' =>$total,
                                'is_posted' =>$posted,
                            );
                            $main_array[] = $data_arr;
                        }

                    }
                }
            }/*print_R($main_array); die();*/
            if(!empty($send_to)){
                  Mail::to($send_to)->send(new PostedSpareParts($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function mail_without_job_card(Request $request)
    {
        $send_to='';
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

            $job_cards = JobCardsCalculation::whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->where([['used_spare_parts_total', '=', '0'], ['new_spare_parts_total', '=', '0']])->get();
            $main_array = array();
            if ($job_cards) {
                foreach ($job_cards as $job_card) {
                    $total = $job_card['new_spare_parts_total'] + $job_card['used_spare_parts_total']+ $job_card['labours_total'];
                    $customer_details = JobCard::where([['is_delete', '=', '0'], ['id', '=', $job_card['job_id']]])->get();
                    if (!empty($customer_details)) {
                      $posted = 'Posted';
                      if(isset($customer_details['status'] )){
                        if ($customer_details['status'] == 'pending' || $customer_details['status'] == 'under_test' || $customer_details['status'] == 'working' || $customer_details['status'] == 'delay' || $customer_details['status'] == 'paint' || $customer_details['status'] == 'print_req' || $customer_details['status'] == 'paid_wait' || $customer_details['status'] == 'clean_polish' || $customer_details['status'] == 'on_change' || $customer_details['status'] == 'cancel_req') {
                            $posted = 'Unposted';
                        }
                      }
                        foreach ($customer_details as $key => $customer_detail) {
                            $data_arr = array(
                                'inv_no' => $job_card->id,
                                'inv_date' => $job_card->created_at,
                                'job_card_no' => $customer_detail['job_no'],
                                'name' => $customer_detail['customer'],
                                'total' => $total,
                                'is_posted' => $posted,
                            );
                            $main_array[] = $data_arr;
                        }
                    }
                }
            }
            if(!empty($send_to)){
                Mail::to($send_to)->send(new UnpostedSpareSpart($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function mail_daily_details(Request $request)
    {
         $send_to='';
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }
            $job_cards = JobCardPayment::where(["is_delete" => 0])->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
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
                    $user_name = User::where([["is_delete" ,'=' ,0],['id','=',$job_card->user_id]])->first();
                    if(!empty(@$user_name)){

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
            if(!empty($send_to)){
              Mail::to($send_to)->send(new DailyPayment($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


    public function mail_daily_summery(Request $request)
    {
        $send_to='';
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

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
                        if ($payment_deatil->pay_by == '1' && $payment_deatil->amount) {

                            $cash = ($cash + $payment_deatil->amount);
                        }
                        if ($payment_deatil->pay_by == '2' && $payment_deatil->amount) {
                            $knet = ($knet + $payment_deatil->amount);
                        }
                        $data_arr = array(
                            'date' => $i,
                            'cash' => $cash,
                            'knet' => $knet,
                            'total' => $cash + $knet
                        );
                    }
                    $main_array[] = $data_arr;
                }
            }
            if(!empty($send_to)){
              Mail::to($send_to)->send(new DailySummery($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function mail_all_sp_part_purchase(Request $request)
    {
        $send_to='';
        try {
            $data = $request->all();
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

            $all_purchases = NewSparePurchase::where(["is_delete" => 0])->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
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
            if(!empty($send_to)){
              Mail::to($send_to)->send(new AllSparePurchase($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }



    public function mail_users_target_report(Request $request)
    {
       $to='';
        try {
            $data = $request->all();
            if(isset($data['data'])){
             $to = $data['data'];
              }

            $new_spare_parts = NewSpareParts::where(['is_delete' => 0])->get();
            $main_array = array();
            if ($new_spare_parts) {
                foreach ($new_spare_parts as $new_spare_part) {
                    $data_arr = array(
                        'item_code' => $new_spare_part->item_code,
                        'item_name' => $new_spare_part->item_name,
                        'balance' => $new_spare_part->balance,
                        'available' => $new_spare_part->available
                    );
                    $main_array[] = $data_arr;
                }
            }
            if(!empty($to)){
                Mail::to($to)->send(new UserTargetReport($main_array));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


    public function mail_end_of_day(Request $request)
    {
          $to ='';
        try {
             $data = $request->all();
             if(isset($data['data'])){
              $to = $data['data'];
               }

            $current=date("Y/m/d");
            $payment_details = JobCardPayment::whereDate('created_at','=',$current)->where(['is_delete' => 0])->get();
            $main_array = array();
            $payment_type_arr = array("1" => "CASH", "2" => "K-NET","3" => "VISA","4" => "MASTER");
            if ($payment_details) {
                foreach ($payment_details as $payment_detail) {
                    $user_info = User::where([['is_delete', '=', 0], ['id', '=', $payment_detail->user_id]])->get();
                    if(@$user_info[0]['name']){
                        //print_r($user_info); die();     
                       $data_arr = array(
                           'shift_date' => @$payment_detail['created_at'],
                           'user_name' => @$user_info[0]['name'],
                           'paymnet_type' =>$payment_type_arr[@$payment_detail['pay_by']],
                           'amount' =>@$payment_detail['amount']

                       );
                   $main_array[] = $data_arr;
                   }
                }
            }
            if(!empty($to)){
                Mail::to($to)->send(new EndTargetReport($main_array));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


    public function mail_spare_parts_net_profit(Request $request)
    {
        $send_to='';
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
            if(isset($data['data']['send_to'])){
            $send_to=$data['data']['send_to'];
            }

            $new_spare_parts = NewSparePurchase::where(['is_delete' => 0])->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get();
            if($new_spare_parts){
                foreach ($new_spare_parts as $key => $new_spare_part) {
                        $item_code=$new_spare_part['item_code'];
                        $purchaseQty=$new_spare_part['purchase_qty'];
                        $purchaseTotal=$new_spare_part['total_amt'];
                        // sales
                        $sales_details=CustomersNewSpareParts::where([['item_code','=',$item_code ],['is_delete' ,'=' ,'0']])->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get()->count();
                        if($sales_details){
                           $salesQty=$sales_details;
                           $salesTotal=$new_spare_part['sale_price']* $salesQty;
                        }
                        // return
                        $return_details=SparePartsReturn::where('item_code','=',$item_code)->whereDate('created_at', '>=', $from_date)->whereDate('created_at', '<=', $to_date)->get()->count();
                        if($return_details){
                           $returnQty=$return_details;
                           $returnTotal=$new_spare_part['sale_price']* $returnQty;
                        }
                        $netProfit= (int)$purchaseTotal-((int)$salesTotal + (int)$returnTotal);
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
            if(!empty($send_to)){
              Mail::to($send_to)->send(new SparePartNetProfit($main_array, $from_date, $to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }


    public function mail_inventory(Request $request)
    {
       $to = '';
        try {
             $data = $request->all();
             if(isset($data['data'])){
              $to = $data['data'];
               }

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
            if(!empty($to)){
              Mail::to($to)->send(new Inventory($main_array));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    // public function renew_visa(){
    //    $user_id=$_POST['user_id'];
    //    if($user_id){
    //       $mail_id = User::where('id',$user_id )->get();
    //       if($mail_id){
    //            foreach ($mail_id as $key => $value) {
    //                $email=$value['email'];
    //                $visa_end_date=$value['visa_end'];
    //            }
    //       }
    //    }
    //     //echo $visa_end_date; die();
    //    if(!empty($visa_end_date) && !empty($email)){
    //      Mail::to($email)->send(new VisaRenew($visa_end_date));
    //    }

    // }

    // public function renew_vacation(){
    //    $user_id=$_POST['user_id'];
    //    if($user_id){
    //       $mail_id = User::where('id',$user_id )->get();
    //       if($mail_id){
    //            foreach ($mail_id as $key => $value) {
    //                $email=$value['email'];
    //            }
    //       }
    //       $end_date = UsersVacations::where('id',$user_id )->get();
    //       if($end_date){
    //            foreach ($end_date as $key => $value) {
    //               $vacation_end_date=$value['end_date'];
    //               $vacation_resume_date=$value['resume_date'];
    //            }
    //       }
    //    }
    //     //echo $visa_end_date; die();
    //    if(!empty($vacation_end_date) && !empty($email) && !empty($vacation_resume_date)){
    //      Mail::to($email)->send(new VacationRenew($vacation_end_date,$vacation_resume_date));
    //    }

    // }

    public function mail_post_sp_part_purchase(Request $request){
         $user = Auth::user();
        try {

            $data = $request->all();
            if(isset($data['data'])){
              $to = $data['data'];
               }
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCard::with("job_card_calculation")->where([['is_delete','=' ,0],['status','=','delivery']])->whereDate('entry_date', '>=', $from_date)->whereDate('entry_date', '<=', $to_date)->get();
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

            if(!empty($to)){
              Mail::to($to)->send(new POSTSparePurchase($main_array,$from_date,$to_date));
            }
            
           
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }

    public function mail_unpost_sp_part_purchase(Request $request){
        $user = Auth::user();
        try {
            $data = $request->all();
            if(isset($data['data'])){
              $to = $data['data'];
               }
            $from_date = $data['data']['from_date'];
            $to_date = $data['data']['to_date'];
            $job_cards = JobCard::with("job_card_calculation")->where([['is_delete','=' ,0],['status','!=','delivery']])->whereDate('entry_date', '>=', $from_date)->whereDate('entry_date', '<=', $to_date)->get();
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
            
            if(!empty($to)){
              Mail::to($to)->send(new UNPOSTSparePurchase($main_array,$from_date,$to_date));
            }

        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
    }
    
    /** By rohit supplier purchase report */
    public function mail_supplier_report(Request $request){
        
        $user = Auth::user();
        DB::enableQueryLog();
        try {
            
            $data = $request->all();  
            if(isset($data['data'])){
                $to = $data['data']['send_to'];
            }      
            $supplier = $data['data']['supplier'];
            $period = (isset($data['data']['supplier_wise_report'])) ? $data['data']['supplier_wise_report'] : "";
            $all_purchases = NewSparePurchase::where(["is_delete" => 0 ])->where(["supplier_name"=>$supplier]);
            $from_date = "";
            $to_date = "";
            $week = 1;
            $year = date("Y");
            $month = date("m");
            if($period){
                if($period == "1")
                {
                    // while($week<=5)
                    // {
                    //     $weekDate =  explode(" - ",$this->getFirstandLastDate($year, $month, $week));
                    //     $from_date = $weekDate[0];
                    //     $to_date = $weekDate[1];
                    // }
                    $from_date = date("Y-m-d", strtotime('monday this week'));
                    $to_date = date("Y-m-d", strtotime('sunday this week'));
                }
                elseif($period == "2"){
                    $from_date = date('Y-m-01');
                    $to_date = date('Y-m-t');
                }
                else{
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
                    if($all_purchase->inv_type=='1'){$inv_type='Type1';}else{$inv_type='Type2';}
                    $data_arr = array(
                        'date' => $all_purchase->date,
                        'inv_no' => $all_purchase->inv_no,
                        'inv_type' => $inv_type,
                        'supplier_name' => $all_purchase->supplier_name,
                        'item_code' => $all_purchase->item_code,
                        'item_name' =>$all_purchase->item_name,
                        'quantity' =>$all_purchase->purchase_qty,
                        'price' =>$all_purchase->total_amt
                    );
                    $main_array[] = $data_arr;


                }
            }
           // echo $to;exit;
            if(!empty($to)){
                Mail::to($to)->send(new SupplierSpearPartsMail($main_array,$from_date,$to_date));
            }
  
        } catch (Exception $ex) {
            return back()->withError($ex->getMessage())->withInput();
        }
     }
    /**End */
}
