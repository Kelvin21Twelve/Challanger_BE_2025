<?php

namespace App\Http\Controllers\API;

use App\JobCard;
use App\JobCardPayment;
use App\JobCardsCalculation;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Customer;
use App\CustomersLabour;
use App\CustomersNewSpareParts;
use App\CustomersUsedSpareParts;
use App\CabNo;
use App\NewSpareParts;
use App\CarEngine;
use DateTime;
use DateTimeZone;
use App\Vehicle;
use App\Labour;
use App\LabourServiceType;
use App\NewSparePurchase;
use App\Notification;



class JobCardController extends Controller
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get_job_cards(Request $request)
    {
        $balance = '';
        try {
            $status = $request->input("status");
            $cust_id = $request->input("cust_id");
            $cabs_obj = CabNo::with("job_card");
            if ($cust_id) {
                $cust_arr = explode(",", $cust_id);
            } else {
                $cust_arr = array();
            }
            switch ($status) {
                case "allempty":
                    //$cabs_array = $cabs_obj->get();
                    $cabs_array = $cabs_obj->where("job_id", "=", '0')->get();
                    break;
                case "allstatus_sortempty":
                case "allemptystatus_sort":
                    $cabs_array = $cabs_obj->orderBy("job_status")->get();
                    break;
                case "allstatus_sort":
                    $cabs_array = $cabs_obj->where("job_id", "!=", null)->orderBy("job_status")->get();
                    break;
                case "minestatus_sort":

                    $user_id = $this->request->user()->id;
                    $cabs_array = $cabs_obj->whereHas('job_card', function ($cabs_obj) use ($user_id) {
                        $cabs_obj->where('user_id', '=', $user_id);
                    })->orderBy("job_status")->get();
                    break;
                case "":
                case "all":
                    $cabs_array = $cabs_obj->where("job_id", "!=", null)->get();
                    break;
                case "mine":
                    $user_id = $this->request->user()->id;
                    $cabs_array = $cabs_obj->whereHas('job_card', function ($cabs_obj) use ($user_id) {
                        $cabs_obj->where('user_id', '=', $user_id);
                    })->get();

                    break;
                default:

                    // $cabs_array   = JobCard::where('status', $status)->update(['status' => 'delivery']);
                    $cabs_array = $cabs_obj->whereHas('job_card', function ($cabs_obj) use ($status) {
                        $cabs_obj->where('status', '=', $status);
                    })->get();
                    break;
                }
                // echo"<pre>";print_r($cabs_array); die();
            $main_array = array();
            $status_count = array(
                'working' => "0",
                'delay' => "0",
                'under_test' => "0",
                'delivery' => "0",
                'paint' => "0",
                'cancel_req' => "0",
                'print_req' => "0",
                'paid_wait' => "0",
                'clean_polish' => "0",
                'on_change' => "0",
                'pending' => "0"
            );
            $cabs_obj_status = CabNo::with("job_card");
            foreach ($cabs_obj_status->get() as $value) {
                if (isset($value->job_id) && $value->job_id) {
                    $job_card = JobCard::with("customer_details")->with("vehicle_details", "vehicle_details.view:id,make", "vehicle_details.type:id,model", "vehicle_details.color:id,color", "vehicle_details.agency:id,agency")->where(['id' => $value->job_id, "is_delete" => 0])->first();
                    if ($job_card) {
                        $status_count[$job_card['status']] += 1;
                    }
                }
            }
            foreach ($cabs_array as $value) {
                if (isset($value->job_id) && $value->job_id) {
                    if (count($cust_arr) > 0) {
                        $job_card = JobCard::with("customer_details")->with("vehicle_details", "vehicle_details.view:id,make", "vehicle_details.type:id,model", "vehicle_details.color:id,color", "vehicle_details.agency:id,agency")->whereIn('customer_id', $cust_arr)->where(['id' => $value->job_id, "is_delete" => 0])->first();
                    } else {
                        $job_card = JobCard::with("customer_details")->with("vehicle_details", "vehicle_details.view:id,make", "vehicle_details.type:id,model", "vehicle_details.color:id,color", "vehicle_details.agency:id,agency")->where(['id' => $value->job_id, "is_delete" => 0])->first();
                    }
                    if ($job_card) {
                        $array = array(
                            'id' => $job_card['id'],
                            'vehicle_id' => $job_card['vehicle'],
                            'job_no' => $job_card['job_no'],
                            'cab_no' => $value->cab_no,
                            'cust_name' => ($job_card['customer_details'])?($job_card['customer_details']->cust_name):'',
                            'cust_name_id' => ((string) $job_card['customer_details'])?(string) $job_card['customer_details']->id:'',
                            'applied_desc' => $job_card['applied_desc'],
                            'phone' => ($job_card['customer_details'])?$job_card['customer_details']->phone:'',
                            'plate_no' => $job_card['vehicle_details']->plate_no,
                            'view' => $job_card['vehicle_details']['view']->make,
                            'type' => $job_card['vehicle_details']['type']->model,
                            'view_type' => $job_card['vehicle_details']['view']->make . " - " . $job_card['vehicle_details']['type']->model,
                            'model' => $job_card['vehicle_details']->model,
                            'color' => $job_card['vehicle_details']['color']->color,
                            'agency' => $job_card['vehicle_details']['agency']->agency,
                            'status' => $job_card['status'],
                            'entry_date' => $job_card['entry_date'],
                            'entry_time' => $job_card['entry_time'],
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
                        //$status_count[$job_card['status']] += 1;
                        $main_array[] = $array;
                    }
                }
                //  else {
                //     $main_array[] = array(
                //         'cab_no' => $value->cab_no,
                //         'empty' => true
                //     );
                // }
            }
            //echo"<pre>";print_r($main_array);exit;
            $view = view("job_list", compact('main_array'))->render();
            return response()->json(['success' => true, 'data' => $main_array,'view' => $view, 'status_count' => $status_count]);
            
            return response()->json(['view' => $view, 'status_count' => $status_count]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function get_jobCard_notifications(){   
        $jobCard_notifications =  Notification::where('job_id' ,'!=','NULL')->where('created_at', '>=', date('Y-m-d').' 00:00:00')->orderBy('id','ASC')->get();
        // echo"<pre>";print_r($jobCard_notifications);exit;

        foreach($jobCard_notifications as $item){
            if($item['job_card_status']=='working'){
                $item['job_card_status']='Working';
            }else if($item['job_card_status']=='delay'){
                $item['job_card_status']='Delay';
            }else if($item['job_card_status']=='under_test'){
                $item['job_card_status']='Under Testing';
            }else if($item['job_card_status']=='delivery'){
                $item['job_card_status']='Delivery';
            }else if($item['job_card_status']=='paint'){
                $item['job_card_status']='Paint';
            }else if($item['job_card_status']=='cancel_req'){
                $item['job_card_status']='Cancel Request';
            }else if($item['job_card_status']=='print_req'){
                $item['job_card_status']='Print Request';
            }else if($item['job_card_status']=='paid_wait'){
                $item['job_card_status']='Paid Wait';
            }else if($item['job_card_status']=='clean_polish'){
                $item['job_card_status']='Clean Polish';
            }else if($item['job_card_status']=='on_change'){
                $item['job_card_status']='On Change';
            }else if($item['job_card_status']=='pending'){
                $item['job_card_status']='Pending';
            }
            $cab_no=CabNo::where('job_id', $item['job_id'])->first();
            $item['cab_no']=$cab_no['cab_no'];

            // echo"<pre>";print_r($cab_no);
        }

        if($jobCard_notifications){
            return response()->json(['success' => true, 'jobCard_notifications' => $jobCard_notifications]);
        }else{
            return response()->json(['success' => false, 'data' => '']);
        } 
    }
    public function get_Empty_job_cards(Request $request)
    {
        $balance = '';
        try {
            $status = $request->input("status");
            $cust_id = $request->input("cust_id");
            $cabs_obj = CabNo::with("job_card");
            if ($cust_id) {
                $cust_arr = explode(",", $cust_id);
            } else {
                $cust_arr = array();
            }
            switch ($status) {
                case "allempty":
                    //$cabs_array = $cabs_obj->get();
                    $cabs_array = $cabs_obj->where("job_id", "=", '0')->get();
                    break;
                case "allstatus_sortempty":
                case "allemptystatus_sort":
                    $cabs_array = $cabs_obj->orderBy("job_status")->get();
                    break;
                case "allstatus_sort":
                    $cabs_array = $cabs_obj->where("job_id", "!=", null)->orderBy("job_status")->get();
                    break;
                case "minestatus_sort":

                    $user_id = $this->request->user()->id;
                    $cabs_array = $cabs_obj->whereHas('job_card', function ($cabs_obj) use ($user_id) {
                        $cabs_obj->where('user_id', '=', $user_id);
                    })->orderBy("job_status")->get();
                    break;
                case "":
                case "all":
                    $cabs_array = $cabs_obj->where("job_id", "!=", null)->get();
                    break;
                case "mine":
                    $user_id = $this->request->user()->id;
                    $cabs_array = $cabs_obj->whereHas('job_card', function ($cabs_obj) use ($user_id) {
                        $cabs_obj->where('user_id', '=', $user_id);
                    })->get();

                    break;
                default:

                    // $cabs_array   = JobCard::where('status', $status)->update(['status' => 'delivery']);
                    $cabs_array = $cabs_obj->whereHas('job_card', function ($cabs_obj) use ($status) {
                        $cabs_obj->where('status', '=', $status);
                    })->get();
                    //print_r($cabs_array); die();
                    break;
            }
            $main_array = array();
            $status_count = array(
                'working' => "0",
                'delay' => "0",
                'under_test' => "0",
                'delivery' => "0",
                'paint' => "0",
                'cancel_req' => "0",
                'print_req' => "0",
                'paid_wait' => "0",
                'clean_polish' => "0",
                'on_change' => "0",
                'pending' => "0"
            );
            $cabs_obj_status = CabNo::with("job_card");
            // foreach ($cabs_obj_status->get() as $value) {
            //     if (isset($value->job_id) && $value->job_id) {
            //         $job_card = JobCard::with("customer_details")->with("vehicle_details", "vehicle_details.view:id,make", "vehicle_details.type:id,model", "vehicle_details.color:id,color", "vehicle_details.agency:id,agency")->where(['id' => $value->job_id, "is_delete" => 0])->first();
            //         if ($job_card) {
            //             $status_count[$job_card['status']] += 1;
            //         }
            //     }
            // }
            foreach ($cabs_array as $value) {
                if ($value->job_id==0) {
                    $main_array[] = array(
                        'cab_no' => $value->cab_no,
                        'empty' => true
                    );
                }
            }
            // echo"<pre>";print_r($main_array);exit;
            $view = view("empty_job_list", compact('main_array'))->render();
            return response()->json(['view' => $view]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(Request $request)
    {
        $job_status = array();
        $user_id = $this->request->user()->id;
        $data = $request->all();
        $customer_id = $data['customer_id'];
        $vehicle = $data['vehicle'];
        $is_already_exists = JobCard::where([['customer_id', '=', $customer_id], ['vehicle', '=', $vehicle], ["is_delete", '=', 0], ['status', '!=', 'delivery']])->count();
        // print_r($is_already_exists); die();
        if ($is_already_exists == 0) {
            $JobCard = $this->Add_job_card($data, $request, $user_id);
            if ($JobCard) {
                return response()->json(['success' => true, 'data' => $JobCard]);
            }
        } else {
            return response()->json(['success' => false, 'msg' => 'job with this customer and vehicle is already assigned']);
        }

        // if($is_already_exists!=0){
        //         $if_exists = JobCard::
        //         where(['customer_id' => $customer_id, 'vehicle' => $vehicle, "is_delete" => 0])->get();

        //         foreach ($if_exists as $key => $value) {
        //               array_push($job_status, $value['status']);
        //               //$job_status[]=$value['status'];
        //         }
        //         //echo end($job_status); die();
        //           //print_r($job_status); die();
        //         if (end($job_status) =='delivery')
        //             {
        //                 $JobCard=$this->Add_job_card($data,$request,$user_id);
        //                 if($JobCard){
        //                     return response()->json(['success' => true, 'data' => $JobCard]);
        //                 }

        //         }else{
        //               return response()->json(['success' => false, 'msg' => 'job with this customer and vehicle is already assigned']);
        //         }
        // }

    }
    public function payment_refund(Request $request, $id)
    {
        $job_card_calc = JobCardsCalculation::where(['job_id' => $id])->first();
        print_r($job_card_calc->balance);exit;
        
        if (isset($job_card_calc->balance) && $job_card_calc->balance < 0) {
            
            $job_card_details = JobCardsCalculation::where(['job_id' => $id])->update(['balance' => 'paid_wait']);

            $job_card_calc->job_id = (int) $id;
            $job_card_calc->balance =round($customer_used_spare_parts_total, 3);
            //print_r($job_card_calc);
            
            $job_card_calc->save();

            return response()->json(['success' => true, 'data' => $job_card_calc]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }
    public function updateJobDiscount(Request $request, $id)
    {
                 $JobCard = JobCard::find($id);
        
        if ($JobCard) {
            
            $JobCard->applied_desc = $request->input("discount");
            $date_obj = new DateTime('now', new DateTimeZone('Asia/Kolkata'));

            $JobCardup=JobCard::where('id',$id);
            $JobCard->update(array('applied_desc' => $request->input("discount")));

            if($JobCardup)
            {

                $job_cal_info =  JobCardsCalculation::where('job_id', '=', $id)->where('is_delete', '=', 0)->first();
                if($job_cal_info->balance > 0)
                {
                   
                    $balance = $job_cal_info->balance - $request->input("discount");
                }
                else
                {
                    $balance = $job_cal_info->balance - $request->input("discount");
                }
                
                $job_cal_info->update(array('balance' => $balance));
            }

            return response()->json(['success' => true, 'data' => $job_cal_info]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    public function update(Request $request, $id)
    {
        $JobCard = JobCard::find($id);
        $job_info =  JobCardPayment::where('job_id', '=', $id)->where('is_delete', '=', 0)->first();
        $job_cal_info =  JobCardsCalculation::where('job_id', '=', $id)->where('is_delete', '=', 0)->first();
        // echo "<pre>";
        // print_r($job_cal_info);
        // print_r($id);
        // exit;
        if ($job_cal_info) {
            if (($job_cal_info['grand_total'] != 0) && ($job_cal_info['balance'] != 0)) {
                # code...
               //    echo $job_cal_info['balance'];exit;
                if ($request->status == 'delivery' && $job_cal_info['balance'] > 0) {
                    //     echo "1.1";
                    //    die;
                    return response()->json(['success' => false, 'data' => "",'msg'=>"Please pay remaining amount after you can change status to delivery."]);
                } else {
                    // echo "1.2";
                    // die;
                    if ($JobCard) {
                        $JobCard->approved = "";
                        $JobCard->returned = "";
                        $JobCard->warranty = "";
                        $JobCard->update($request->all());
                        CabNo::where(['job_id' => $JobCard->id])->update(array('job_status' => $JobCard->status));
                        
                        //Notification for change status for job card
                        $noti = new Notification();
                        $noti->job_card_status = $request->status;
                        $noti->job_id = $id;
                        $noti->status = "Job Card Status";
                        $noti->user_id = $this->request->user()->id;
                        $noti->save();
                        

                        // return response()->json(['success' => true, 'notification' => array("item_code" => $item_code, "item_name" => $item_name, "status" => "new")]);
                        return response()->json(['success' => true, 'data' => $JobCard]);
                    } else {
                        return response()->json(['success' => false, 'data' => ""]);
                    }
                }
            } else if (($job_cal_info['grand_total'] != 0) && ($job_cal_info['balance'] == 0)) {
                // echo "2";
                // die;
                if ($request->status == 'delivery') {
                    if ($JobCard) {
                        $JobCard->approved = "";
                        $JobCard->returned = "";
                        $JobCard->warranty = "";
                        $date_obj = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
                        
                        $JobCardup=JobCard::where('id',$id);
                        $JobCardup->update(array('delivery_date' => $date_obj->format('Y-m-d H:i:s'),
                    'status'=>'delivery'));
                        $cab_details = CabNo::where('job_id', $id)->update(['job_id' => '0', 'job_status' => 'NULL']);
                        
                        //Notification for change status for job card
                       
                        $noti = new Notification();
                        $noti->job_card_status = $request->status;
                        $noti->job_id = $id;
                        $noti->status = "Job Card Status";
                        $noti->user_id = $this->request->user()->id;
                        $noti->save();
                        // CabNo::where(['job_id' => $JobCard->id])->update(array('job_status' => $JobCard->status));
                        return response()->json(['success' => true, 'data' => $JobCard]);
                    } else {
                        return response()->json(['success' => false, 'data' => ""]);
                    }
                } else {
                    if ($JobCard) {
                        $JobCard->approved = "";
                        $JobCard->returned = "";
                        $JobCard->warranty = "";
                        $JobCard->update($request->all());
                        // $cab_details = CabNo::where('job_id', $id)->update(['job_id' => '0', 'job_status' => 'NULL']);
                        CabNo::where(['job_id' => $JobCard->id])->update(array('job_status' => $JobCard->status));
                        //Notification for change status for job card
                       
                        $noti = new Notification();
                        $noti->job_card_status = $request->status;
                        $noti->job_id = $id;
                        $noti->status = "Job Card Status";
                        $noti->user_id = $this->request->user()->id;
                        $noti->save();
                        return response()->json(['success' => true, 'data' => $JobCard]);
                    } else {
                        return response()->json(['success' => false, 'data' => ""]);
                    }
                }
            } else if (($job_cal_info['grand_total'] == 0) && ($job_cal_info['balance'] == 0)) {
                //     echo "1.1";
                //    die;
                if ($request->status == 'delivery') {
                    return response()->json(['success' => false, 'data' => ""]);
                } else {
                    // echo "1.2";
                    // die;
                    if ($JobCard) {
                        $JobCard->approved = "";
                        $JobCard->returned = "";
                        $JobCard->warranty = "";
                        $JobCard->update($request->all());
                        CabNo::where(['job_id' => $JobCard->id])->update(array('job_status' => $JobCard->status));

                        //Notification for change status for job card
                        $noti = new Notification();
                        $noti->job_card_status = $request->status;
                        $noti->job_id = $id;
                        $noti->status = "Job Card Status";
                        $noti->user_id = $this->request->user()->id;
                        $noti->save();

                        return response()->json(['success' => true, 'data' => $JobCard]);
                    } else {
                        return response()->json(['success' => false, 'data' => ""]);
                    }
                }
            } else {
                // echo "3";
                // die;
                if ($JobCard) {
                    $JobCard->approved = "";
                    $JobCard->returned = "";
                    $JobCard->warranty = "";
                    $JobCard->update($request->all());
                    CabNo::where(['job_id' => $JobCard->id])->update(array('job_status' => $JobCard->status));

                    //Notification for change status for job card
                    $noti = new Notification();
                    $noti->job_card_status = $request->status;
                    $noti->job_id = $id;
                    $noti->status = "Job Card Status";
                    $noti->user_id = $this->request->user()->id;
                    $noti->save();
                    
                    return response()->json(['success' => true, 'data' => $JobCard]);
                } else {
                    return response()->json(['success' => false, 'data' => ""]);
                }
            }
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }

        // if ($JobCard) {
        //     $JobCard->approved = "";
        //     $JobCard->returned = "";
        //     $JobCard->warranty = "";
        //     $JobCard->update($request->all());
        //     CabNo::where(['job_id' => $JobCard->id])->update(array('job_status' => $JobCard->status));
        //     return response()->json(['success' => true, 'data' => $JobCard]);
        // } else {
        //     return response()->json(['success' => false, 'data' => ""]);
        // }
    }

    public function delete(Request $request, $id)
    {
        $JobCard = JobCard::find($id);
        if ($JobCard) {
            $JobCard->is_delete = 1;
            $JobCard->save();
            CabNo::where(['cab_no' => $JobCard->cab_no])->update(['job_id' => '0', "job_status" => Null]);
            return response()->json(['success' => true, 'job_id' => $id]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    public function search_master(Request $request)
    {
        $cust_name = $request->input("cust_name");
        $balance = '';
        $car_make = $request->input("car_make");
        $job_card_no = $request->input("job_card_no");
        $car_view = $request->input("car_view");
        $plate_no = $request->input("plate_no");
        $car_type = $request->input("car_type");

        $is_date_btw = $request->input("is_date_btw");
        $from_date = $request->input("from_date");
        $to_date = $request->input("to_date");
        $status = $request->input("status");

        $query = DB::table('job_cards');
        $cases = array();
        (isset($cust_name) && $cust_name !== '') ? $cases[] = "cust_name" : '';
        (isset($status) && $status !== '') ? $cases[] = "status" : '';
        (isset($cust_name) && $cust_name !== '') ? $cases[] = "cust_name" : '';
        (isset($car_make) && $car_make !== '') ? $cases[] = "car_make" : '';
        (isset($job_card_no) && $job_card_no !== '') ? $cases[] = "job_card_no" : '';
        (isset($car_view) && $car_view !== '') ? $cases[] = "car_view" : '';
        (isset($plate_no) && $plate_no !== '') ? $cases[] = "plate_no" : '';
        (isset($car_type) && $car_type !== '') ? $cases[] = "car_type" : '';
        (isset($is_date_btw) && $is_date_btw !== '') ? $cases[] = "is_date_btw" : '';



        foreach ($cases as $case) {
            switch ($case) {
                case "cust_name":
                    $query->where('customer_id', '=', $cust_name);
                    break;
                case "status":
                    $query->where('status', '=', $status);
                    break;
                case "car_make":
                    $vehicle = Vehicle::where([['car_make', '=', $car_make], ['is_delete', '=', '0']])->select("id")->get();
                    $custs = array();
                    if (!$vehicle->isEmpty()) {
                        foreach ($vehicle as $value) {
                            $custs[] = $value['id'];
                        }
                    }
                    $query->whereIn('vehicle', $custs);
                    break;
                case "job_card_no":
                    $query->where('job_no', '=', $job_card_no);
                    break;
                case "car_view":
                    $vehicle = Vehicle::where([['car_view', '=', $car_view], ['is_delete', '=', '0']])->select("id")->get();
                    $custs = array();
                    if (!$vehicle->isEmpty()) {
                        foreach ($vehicle as $value) {
                            $custs[] = $value['id'];
                        }
                    }
                    $query->whereIn('vehicle', $custs);
                    break;
                case "plate_no":
                    $vehicle = Vehicle::where('plate_no', '=', $plate_no)->select("id")->get();
                    $custs = array();
                    if (!$vehicle->isEmpty()) {
                        foreach ($vehicle as $value) {
                            $custs[] = $value['id'];
                        }
                    }
                    $query->whereIn('vehicle', $custs);
                    break;
                case "car_type":
                    $vehicle = Vehicle::where('car_type', '=', $car_type)->select("id")->get();
                    $custs = array();
                    if (!$vehicle->isEmpty()) {
                        foreach ($vehicle as $value) {
                            $custs[] = $value['id'];
                        }
                    }
                    $query->whereIn('vehicle', $custs);
                    break;
                case "is_date_btw":
                    $query->whereBetween('entry_date', array($from_date, $to_date));
            }
        }
        $cabs_array = $query->where('is_delete', '=', 0)->get();
        $main_array = array();
        foreach ($cabs_array as $value) {
            if (isset($value->id) && $value->id) {
                $job_card = JobCard::with("customer_details")->with("vehicle_details", "vehicle_details.view:id,make", "vehicle_details.type:id,model", "vehicle_details.color:id,color", "vehicle_details.agency:id,agency")->where(['id' => $value->id, "is_delete" => 0])->first();
                if ($job_card) {
                    $job_card_calculation = JobCardsCalculation::where('job_id', $job_card->id)->get();
                    if ($job_card_calculation) {
                        foreach ($job_card_calculation as $key => $value) {
                            $balance = $value['balance'];
                        }
                    }
                    $array = array(
                        'id' => $job_card['id'],
                        'vehicle_id' => $job_card['vehicle'],
                        'job_no' => $job_card['job_no'],
                        'cab_no' => $value->cab_no,
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
                        'status' => $job_card['status'],
                        'entry_date' => $job_card['entry_date'],
                        'entry_time' => $job_card['entry_time'],
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
                        'balance' => $balance,
                        'empty' => false
                    );
                    $main_array[] = $array;
                }
            }
        }
        $view = view("job_list_table", compact('main_array'))->render();
        return response()->json(['view' => $view,'data' => $main_array]);
    }

    public function created_by(Request $request)
    {
        $user_id = '';
        $user_name = '';
        $cab_no = '';
        $car_engine = '';
        $job_no = $_POST['job_no'];
        if ($job_no) {
            $job_details =  JobCard::where('job_no', '=', $job_no)->get();
            if ($job_details) {
                foreach ($job_details as $key => $value) {
                    $user_id = $value['user_id'];
                    $cab_no = $value['cab_no'];
                    $car_engine = $value['car_engine'];
                    $applied_desc = $value['applied_desc'];
                }
            }
            if ($user_id) {
                $user_details =  User::where('id', '=', $user_id)->get();
                if ($user_details) {
                    foreach ($user_details as $key => $value) {
                        $user_name = $value['name'];
                        $labour_desc = $value['labour_desc'];
                        $max_desc = $value['max_desc'];
                    }
                }
            }


            return response()->json(['success' => true, 'data' => $user_name,'labour_desc' => $labour_desc, 'cab_no' => $cab_no, 'car_engine' => $car_engine, 'applied_desc' => $applied_desc, 'max_desc' => $max_desc]);

           
        }
    }

    public function get_payment_details()
    {
        $job_no = $_POST['job_no'];
        $job_id = '';
        $status = '';
        $grand_total = '0.00';
        $balance = '0.00';
        if ($job_no) {
            $job_details =  JobCard::where('job_no', '=', $job_no)->get();
        
            if ($job_details) {
                foreach ($job_details as $key => $value) {
                    $job_id = $value['id'];
                    $status = $value['status'];
                    $applied_desc = $value['applied_desc'];
                }
            }

            if ($job_id) {
                $job_info =  JobCardPayment::where('job_id', '=', $job_id,)->where('action', 'credit')->get(['amount', 'pay_by', 'remaining']);
                $job_info_refund =  JobCardPayment::where('job_id', '=', $job_id,)->where('action', 'refund')->get(['id','amount', 'pay_by', 'remaining']);
                //echo $job_id;exit;
                $job_card_calculation =  JobCardsCalculation::where('job_id', '=', $job_id)->get(['grand_total', 'balance', 'labour_disc']);
                // print_r($job_info);
                 //print_r($job_id);
                // print_r($job_card_calculation);die;
                //echo 'sfss';exit;
                //$labour_disc=$job_card_calculation[0]['labour_disc'];
                $balance = 0;
                $refund_balance = 0;
                if ($job_card_calculation) {
                    foreach ($job_card_calculation as $key => $value1) {
                        $grand_total = $value1['grand_total'];
                        //$balance = $value1['balance'];
                        $labour_disc = $value1['labour_disc'];
                    }
                    if ($grand_total > 0) {
                        $grand_total = $grand_total - $applied_desc;
                    }
                }
                if ($job_info) {
                    $overdue = 0;
                    foreach ($job_info as $key => $value2) {
                        $balance = $balance + $value2['amount'];
                        //$grand_total = $value2['grand_total'];
                        // print_r($value2['amount']);
                        if ($value2['pay_by'] == 2) {
                            $overdue = $overdue + $value2['amount'];
                            # code...
                        }
                        // $balance = $value2['balance'];

                    }
                    $balance = $grand_total - $balance;
                    //echo $balance;exit;
                }
               // echo '<pre>';
               // print_r($balance);exit;
                $result = 0;
                 if ($job_info_refund) {
                    $overdue = 0;
                    foreach ($job_info_refund as $key => $value2) {
                        $refund_balance = $refund_balance + $value2['amount'];
                    }
                    $condition = 0;
                    // 1. Both negative
                    if ($balance < 0 && $refund_balance < 0) {
                        $result = $balance + abs($refund_balance);
                        $condition = 1;
                    }

                    // 2. X negative, Y positive
                    if ($balance < 0 && $refund_balance > 0) {
                        $result = - (abs($balance) - $refund_balance);
                        $condition = 1;
                    }

                    // 3. X positive, Y negative
                    if ($balance > 0 && $refund_balance < 0) {
                        $result = $balance + abs($refund_balance);
                        $condition = 1;
                    }
                    $balance = $condition == 1 ? $result : $balance;
                    
                    //echo $balance;exit;
                }
               
                //echo $grand_total;exit;
                if ($job_info) {
                    
                    return response()->json(['success' => true, 'data' => $job_info, 'overdue' => $overdue, 'status' => $status, 'balance' => $balance, 'grand_total' => $grand_total,'labour_disc'=>0,'applied_desc'=>$applied_desc]);
                } else {
                    return response()->json(['error' => true, 'data' => '', 'balance' => $balance, 'grand_total' => $grand_total,'labour_disc'=>0]);
                }
            }
        }
    }

    public function Add_job_card($data, $request, $user_id)
    {
        $JobCard = new JobCard($data);
        $JobCard->car_engine = $request->input("car_engine");
        $JobCard->fill($request->all());
        $JobCard->job_no = mt_rand(10000, 99999);

        $date_obj = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $time = $date_obj->format('H:i:s');
        $date = $date_obj->format('Y-m-d');

        $JobCard->entry_date = $date;
        $JobCard->entry_time = $time;
        $JobCard->user_id = $user_id;
        $JobCard->save();
        $cab_no = $JobCard->cab_no;
        CabNo::where(['cab_no' => $cab_no])->update(array('job_id' => $JobCard->id, "job_status" => $JobCard->status));
        return $JobCard;
    }

    // public funtion vehicle_created(){
    //
    // }
    public function get_item_code()
    {
        $new_spare_parts = NewSpareParts::where('is_delete', '=', 0)->get();
        if ($new_spare_parts) {
            return response()->json(['success' => true, 'data' => $new_spare_parts]);
        } else {
            $new_spare_parts = [];
            return response()->json(['success' => false, 'data' => $new_spare_parts]);
        }
    }

    // public function get_item_code_details(Request $request)
    // {
    //     if ($request['id']) {
    //         $new_spare_parts = NewSpareParts::where('is_delete', '=', 0)->where('id', $request['id'])->get();
    //         return response()->json(['success' => true, 'data' => $new_spare_parts]);
    //     } else {
    //         $new_spare_parts = [];
    //         return response()->json(['success' => false, 'data' => $new_spare_parts]);
    //     }
    // }

    public function  insert_notification(Request $request){
        $data=$request->all();
        $item_code=$data['item_code'];
        $item_name = $data["item_name"];
       
        $notidata = NewSparePurchase::where('item_code','=',$item_code)->where("status","=","new")->first();
        if(empty($notidata))
        {   
            $noti = new Notification();
            $noti->item_code = $item_code;
            $noti->item_name = $item_name;
            $noti->status = "new";
            $noti->user_id = $this->request->user()->id;
            $noti->save();
        }
        
        return response()->json(['success' => true, 'notification' => array("item_code"=>$item_code,"item_name"=>$item_name,"status"=>"new")]);
      }
    public function get_item_code_details(Request $request)
    {
        if ($request['id']) {
            $new_spare_parts = NewSpareParts::where('is_delete', '=', 0)->where('id', $request['id'])->get();
            $equal_new_spare_parts = NewSpareParts::where('is_delete', '=', 0)->whereColumn('min_limit' ,'=','balance')->get();
            $less_new_spare_parts = NewSpareParts::where('is_delete', '=', 0)->whereColumn('balance' ,'<','min_limit')->get();
            // echo"<pre>";print_r(count($equal_new_spare_parts));
            // echo"<pre>";print_r(count($less_new_spare_parts));exit;

            if ($equal_new_spare_parts) {
                foreach($equal_new_spare_parts as $val){

                    $noti = new Notification();
                    $noti->item_code = $val['item_code'];
                    $noti->item_name = $val['item_name'];
                    $noti->status = "new";
                    $noti->user_id = $this->request->user()->id;
                    $noti->save();
                }
            }
            if ($less_new_spare_parts) {
                # code...
                foreach($less_new_spare_parts as $val){

                    $noti = new Notification();
                    $noti->item_code = $val['item_code'];
                    $noti->item_name = $val['item_name'];
                    $noti->status = "new";
                    $noti->user_id = $this->request->user()->id;
                    $noti->save();
                }
            }
            
            return response()->json(['success' => true, 'data' => $new_spare_parts]);




        } else {
            $new_spare_parts = [];
            $equal_new_spare_parts = [];
            $less_new_spare_parts = [];
            return response()->json(['success' => false, 'data' => $new_spare_parts]);
        }
    }

    public function get_engine_types(Request $request)
    {
        $engine = CarEngine::where('is_delete', 0)->where('model', $request['model_id'])->get();
        if ($engine) {
            return response()->json(['success' => true, 'data' => $engine]);
        } else {
            $engine = [];
            return response()->json(['success' => false, 'data' => $engine]);
        }
    }
    public function get_complete_jobs(){
        $jobcardinfo=JobCard::select('id','job_no','cab_no','user_id','status','created_at','customer','phone','delivery_date')->where('is_delete',0)->where('status','delivery')->where('delivery_date','!=','')->orderBy('id', 'desc')->get();
        // $jobcardinfo=JobCard::with("customer_details")->with("vehicle_details", "vehicle_details.view:id,make", "vehicle_details.type:id,model", "vehicle_details.color:id,color", "vehicle_details.agency:id,agency")->where('is_delete',0)->where('status','delivery')->get();
        // echo"<pre>";print_r($jobcardinfo);exit;

        $main_array=[];
        foreach ($jobcardinfo as $key => $value) {
            $job_cal_info =  JobCardsCalculation::where('job_id', '=', $value['id'])->where('is_delete', '=', 0)->first();
            if(($job_cal_info['grand_total'] != 0) && ($job_cal_info['balance'] == 0)){
                // $main_array[].array_push($value)
                $value['total_amount']=$job_cal_info['grand_total'];
                array_push($main_array, $value);
            }
        }
        // echo"<pre>";print_r($main_array);exit;
        if ($main_array) {
            return response()->json(['success' => true, 'data' => $main_array]);
        } else {
            return response()->json(['success' => false, 'data' => $main_array]);
        }
    }
    public function get_cab_history(Request $request){
        $cab_no = $request->cab_no;
        if($cab_no=='All'){
            $jobcardinfo=JobCard::select('id','job_no','cab_no','user_id','status','created_at','customer','phone','delivery_date')->where('is_delete',0)->where('status','delivery')->where('delivery_date','!=','')->orderBy('id', 'desc')->get();
        }else{
            $jobcardinfo=JobCard::select('id','job_no','cab_no','user_id','status','created_at','customer','phone','delivery_date')->where('is_delete',0)->where('status','delivery')->where('delivery_date','!=','')->where('cab_no','=',$cab_no)->orderBy('id', 'desc')->get();
        }


        // $CabNoList=JobCard::select('cab_no')->where('is_delete',0)->where('status','delivery')->where('delivery_date','!=','')->orderBy('id', 'desc')->get();
        

        $main_array=[];
        // $cab_array=[];
        if(count($jobcardinfo)>0){
            foreach ($jobcardinfo as $key => $value) {
                $job_cal_info =  JobCardsCalculation::where('job_id', '=', $value['id'])->where('is_delete', '=', 0)->first();
                if(($job_cal_info['grand_total'] != 0) && ($job_cal_info['balance'] == 0)){
                    // $main_array[].array_push($value)
                    $value['total_amount']=$job_cal_info['grand_total'];
                    array_push($main_array, $value);
                    // array_push($cab_array, $value['cab_no']);
    
                }
            }
        }
        // echo"<pre>";print_r($final_array);exit;
        if ($main_array) {
            return response()->json(['success' => true, 'data' => $main_array]);
        } else {
            return response()->json(['success' => false, 'data' => $main_array]);
        }
    }

    

    public function get_complete_job_details(Request $request){
        $job_id = $request->job_id;
        // echo "<pre>"; print_r($job_id); die();

            $job_card = JobCard::with("customer_details")->with("vehicle_details", "vehicle_details.view:id,make", "vehicle_details.type:id,model", "vehicle_details.color:id,color", "vehicle_details.agency:id,agency")->where("is_delete", 0)->where('id', $job_id)->first();
            
            // $cab_no = CabNo::where('job_id', $job_id)->first();
            // echo "<pre>"; print_r($cab_no); die();

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
                    'car_engine' => $job_card['car_engine'],
                    'applied_desc' => $job_card['applied_desc'],
                    'empty' => false
                );
                $main_array['job_card_details'] = $data_arr;
                $main_array['customer_used_spare_parts'] = CustomersUsedSpareParts::where('is_delete', 0)->where('job_id', $job_id)->get();
            // echo "<pre>"; print_r($main_array['customer_used_spare_parts']); die();

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
            } // job card
            $main_array['job_card_calculation'] = JobCardsCalculation::where('job_id', $job_id)->first();
            $main_array['overdue'] = $overdue;
            $image = public_path('/image/challenger2-1.png');
            $main_array['image'] = $image;
            if ($main_array) {
                return response()->json(['success' => true, 'data' => $main_array]);
            } else {
                $main_array = [];
                return response()->json(['success' => false, 'data' => $main_array]);
            }
            // echo "<pre>"; print_r($main_array); die();
    }


}
