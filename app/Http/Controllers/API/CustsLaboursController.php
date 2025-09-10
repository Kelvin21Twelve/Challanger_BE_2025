<?php

namespace App\Http\Controllers\API;

use App\CustomersLabour;
use App\Labour;
use App\JobCardsCalculation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustsLaboursController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
       // echo 'sdfsdf';exit;
        $user_id = $this->request->user()->id;
        $cust_labours = new CustomersLabour();
        $cust_labours->user_id = $user_id;
        $cust_labours->job_id = (int) $request->input("job_id");
        $cust_labours->labour_id = (int) $request->input("id");
        $cust_labours->labour_name = $request->input("name");
        $cust_labours->quantity = 1;
        $cust_labours->price = $request->input("price");
        $total = $cust_labours->price;
        $cust_labours->save();
        $main_arr = $this->CalculationLabours($user_id, $request->input("job_id"), $total, "add", 0);
        return response()->json(['success' => true, 'data' => $cust_labours, 'used_part_calc' => $main_arr['used_part_calc'], "status_calc" => $main_arr['status_calc']]);
    }

    public function update(Request $request, $id) {
             

        $user_id = $this->request->user()->id;
        $cust_labours = CustomersLabour::find($request->id);
        if ($cust_labours) {
            $old_total = $cust_labours->quantity * $cust_labours->price;
            $cust_labours->update($request->all());
            $total = $request->input("quantity") * $request->input("price");
            $main_arr = $this->CalculationLabours($user_id, $request->input("job_id"), $total, "add", $old_total);
            return response()->json(['success' => true, 'data' => $cust_labours, 'used_part_calc' => $main_arr['used_part_calc'], "status_calc" => $main_arr['status_calc']]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    public function CalculationLabours($user_id, $job_id, $total, $action, $old_total) {
        $cust_labours_arr = CustomersLabour::where(['job_id' => $job_id, "is_delete" => 0])->get();
        $customer_labours_total = 0.00;
        foreach ($cust_labours_arr as $u_parts) {
            $customer_labours_total += $u_parts['quantity'] * $u_parts['price'];
        }
        $job_card_calc = JobCardsCalculation::where(['job_id' => $job_id])->first();
        $main_arr = array();
        if ($job_card_calc) { // Update
            $job_card_calc->labours_total = round($customer_labours_total, 3);
            $grand_total = $customer_labours_total + $job_card_calc->new_spare_parts_total + $job_card_calc->used_spare_parts_total;
            $job_card_calc->grand_total = round($grand_total, 3);
            if ($action == "add") {
                $t=($job_card_calc->balance - $old_total) + $total;
                $job_card_calc->balance = round($t, 3);
            } else {
                $t1=$job_card_calc->balance - $total;
                $job_card_calc->balance = round($t1, 3);
            }
            $job_card_calc->save();
            $main_arr["status_calc"] = "update";
        } else { // Insert
            $job_card_calc = new JobCardsCalculation();
            $job_card_calc->user_id = $user_id;
            $job_card_calc->job_id = (int) $job_id;
            $job_card_calc->used_spare_parts_total = 0.00;
            $job_card_calc->new_spare_parts_total = 0.00;
            $job_card_calc->labours_total = round($customer_labours_total, 3);
            $job_card_calc->grand_total = round($customer_labours_total, 3);
            $job_card_calc->balance = round($customer_labours_total, 3);
            $job_card_calc->save();
            $main_arr["status_calc"] = "create";
        }
        $main_arr['used_part_calc'] = array(
            'id' => (string) $job_card_calc->id,
            'user_id' => $job_card_calc->user_id,
            'job_id' => $job_card_calc->job_id,
            'used_spare_parts_total' => $job_card_calc->used_spare_parts_total,
            'new_spare_parts_total' => $job_card_calc->new_spare_parts_total,
            'labours_total' => $job_card_calc->labours_total,
            'grand_total' => $job_card_calc->grand_total,
            'balance' => $job_card_calc->balance
        );
        return $main_arr;
    }

    public function get_cust_labour_list(Request $request){
        $cust_labours = CustomersLabour::where('job_id',$request->job_id)->where('is_delete',0)->get();
        // print_r($cust_labours);exit;
        if (count($cust_labours)>0) {
            return response()->json(['success' => true, 'data' => $cust_labours]);
        } else {

            return response()->json(['success' => false, 'data' => '']);
        }
    }

    public function add_customers_labours(Request $request){
      // print_r($request->labour);exit;
        $get_labour=Labour::where('id',$request->id)->where('is_delete',0)->first();
        //$get_labour=Labour::where('id',$request->labour)->where('is_delete',0)->first();
        if($get_labour){
          //  print_r($get_labour);exit;
            $user_id = $this->request->user()->id;
            $cust_labours = new CustomersLabour();
            $cust_labours->user_id = $user_id;
            $cust_labours->job_id = (int) $request->input("job_id");
            $cust_labours->labour_id = (int) $get_labour['id'];
            $cust_labours->labour_name = $get_labour['name'];
            $cust_labours->quantity = 1;
            $cust_labours->price = $request->input("price");
            $total = $cust_labours->price;
            $cust_labours->save();
            $main_arr = $this->CalculationLabours($user_id, $request->input("job_id"), $total, "add", 0);
            return response()->json(['success' => true, 'data' => $cust_labours, 'used_part_calc' => $main_arr['used_part_calc'], "status_calc" => $main_arr['status_calc']]);

        }else{
            return response()->json(['success' => false, 'data' => '']);

        }

    }

}
