<?php

namespace App\Http\Controllers\API;

use App\CustomersNewSpareParts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JobCard;
use App\JobCardsCalculation;
use App\NewSpareParts;

class CustNewSparePartsController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {

        $available_balance='';
        $main_array = array();
        $user_id = $this->request->user()->id;
        $job_id = $request->input("id");
        $notes = $request->input("notes");
        //$cab_no = $request->input("cab_no");
        $job = JobCard::find($job_id);
        if ($job['notes'] != "") {
            $job['notes'] = $job['notes'] . " " . $notes;
        } else {
            $job['notes'] = $notes;
        }
        
        $job->save();



        //$job->update($job);
        $main_array["job_card"] = array("job_id" => $job_id, "notes" => $job->notes);
        $items = $request->input("item_data");
        $item_array = array();
        foreach ($items as $value) {
            $new_parts = new CustomersNewSpareParts();
            $new_parts->user_id = $user_id;
            $new_parts->job_id = (int) $job_id;
            $new_parts->cab_no = $request->input("cab_no");
            $new_parts->item_code = $value['item_code'];
            $new_parts->item = $value['item_name'];
            $new_parts->item_id = $value['item_id'];
            $new_parts->quantity = $value['quantity'];
            $new_parts->price = $value['sale_price'];
            $new_parts->discount = $value['discount'];
            $new_parts->total = $value['total'];
            $new_parts->agent_price = @$value['agent_price'];
            $total = $new_parts->quantity * $new_parts->price;
            $new_parts->save();
            $this->update_bal($value['item_id'],$value['quantity']);
        }
        $main_arr = $this->CalculationCustNewSpareParts($user_id, (int) $job_id, $total, "add");
        $main_array["items"] = $item_array;
        return response()->json(['success' => true, 'data' => $main_array, 'used_part_calc' => $main_arr['used_part_calc'], "status_calc" => $main_arr['status_calc']]);
    }

    public function CalculationCustNewSpareParts($user_id, $job_id, $total, $action) {
        $cust_new_spare_parts_arr = CustomersNewSpareParts::where(['job_id' => $job_id, "is_delete" => 0])->get();
        $cust_new_spare_parts_total = 0.00;
        foreach ($cust_new_spare_parts_arr as $u_parts) {
            $cust_new_spare_parts_total += ($u_parts['quantity'] * $u_parts['price']) - $u_parts['discount'];
        }
        //echo $cust_new_spare_parts_total; die();
        $job_card_calc = JobCardsCalculation::where(['job_id' => $job_id])->first();
        //print_r($job_card_calc);die();
        $main_arr = array();
        if ($job_card_calc) { // Update
            //echo "update"; die();
            $job_card_calc->new_spare_parts_total = round($cust_new_spare_parts_total, 3);
            $grand_total = $cust_new_spare_parts_total + $job_card_calc->used_spare_parts_total + $job_card_calc->labours_total;

            $job_card_calc->grand_total = round($grand_total, 3);
            if ($action == "add") {
                //echo "add";  die();
                $t=$job_card_calc->balance + $total;
                $job_card_calc->balance = round($t, 3);
            } else {
                //echo "minus";  die();
                $t1=$job_card_calc->balance - $total;
                $job_card_calc->balance = round($t1, 3);
            }
            //print_r($job_card_calc); die();
            $job_card_calc->save();
            $main_arr["status_calc"] = "update";
        } else { // Insert
           // echo "insert"; die();
            $job_card_calc = new JobCardsCalculation();
            $job_card_calc->user_id = $user_id;
            $job_card_calc->job_id = (int) $job_id;
            $job_card_calc->used_spare_parts_total = 0.00;
            $job_card_calc->new_spare_parts_total = round($cust_new_spare_parts_total, 3);
            $job_card_calc->labours_total = 0.00;
            $job_card_calc->grand_total = round($cust_new_spare_parts_total, 3);
            $job_card_calc->balance = round($cust_new_spare_parts_total, 3);
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
        //print_r($main_arr); die();
        return $main_arr;
    }


   public function update_bal($itemcode,$quantity){
    //echo $itemcode ; echo $quantity ;die();
    $available_balance='';
    $new_spare_parts=NewSpareParts:: where('id','=',$itemcode)->get();
    //print_r($new_spare_parts); die();
    if($new_spare_parts){
        foreach($new_spare_parts as $value){
             $available_balance=$value['balance'];
        }
    }
    //die();
    if(!empty($available_balance) && ($quantity <= $available_balance  )){
        $balance=$available_balance-$quantity;
        //echo $balance; die();   
        $Updatebalance = NewSpareParts::where('id', '=',$itemcode)->update(['balance' => $balance]);
        
    }else{ 
        //return response()->json(['success' => false, 'message' => 'You have exceeded your balance count']);
    }
   }

   public function get_new_spare_parts_useds(Request $request){
      if(@$request['job_id']){
        $data=CustomersNewSpareParts::where([['job_id','=',@$request['job_id']],['is_delete','=',0]])->get();
        if($data){
            return response()->json(['success' => true, 'data' => $data]);
        }else{
            return response()->json(['success' => true, 'data' =>'']);
        }
      }
   }

}
