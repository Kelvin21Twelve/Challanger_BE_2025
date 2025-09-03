<?php

namespace App\Http\Controllers\API;

use App\CustomersUsedSpareParts;
use App\UsedSpareParts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JobCardsCalculation;

class CustUsedSparePartsController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }
    

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $used_parts = new CustomersUsedSpareParts();
        $used_parts->user_id = $user_id;
        $used_parts->job_id = (int) $request->input("job_id");
        $used_parts->item_id = (int) $request->input("id");
        $used_parts->item_name = $request->input("item_name");
        $used_parts->quantity = 1;
        $used_parts->price = $request->input("sale_price");
        $total = $used_parts->price;
        $used_parts->save();
        $main_arr = $this->CalculationUsedSpareParts($user_id, $request->input("job_id"), $total, "add", 0);
        return response()->json(['success' => true, 'data' => $used_parts, 'used_part_calc' => $main_arr['used_part_calc'], "status_calc" => $main_arr['status_calc']]);

    }

    public function update(Request $request, $id) {
        $user_id = $this->request->user()->id;
        $used_parts = CustomersUsedSpareParts::find($id);
        $old_qty = $used_parts->quantity;
        $old_total = $used_parts->quantity * $used_parts->price;
        if ($used_parts) {
            $used_parts->update($request->all());
            $total = $request->input("quantity") * $request->input("price");
            /**Rohit Update qty of used spear parts table */
            $item_id = $used_parts->item_id;
            if($old_qty > $request->input("quantity")){
                $used_qty = $old_qty - $request->input("quantity");
            }else{
                $used_qty = $request->input("quantity") - $old_qty;
            }
            $results = UsedSpareParts::where('id',$item_id)->first();
            if ($results) {
                $oldBalance = $results->balance;
                $newBalance = $oldBalance - $used_qty;
                UsedSpareParts::where(['id' => $item_id])->update(["balance" => $newBalance]);
            }
            /**End */
            $main_arr = $this->CalculationUsedSpareParts($user_id, $request->input("job_id"), $total, "add", $old_total);
            return response()->json(['success' => true, 'data' => $used_parts, 'used_part_calc' => $main_arr['used_part_calc'], "status_calc" => $main_arr['status_calc']]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    public function CalculationUsedSpareParts($user_id, $job_id, $total, $action, $old_total) {
        $used_parts_arr = CustomersUsedSpareParts::where(['job_id' => $job_id, "is_delete" => 0])->get();
        $customer_used_spare_parts_total = 0.00;
        foreach ($used_parts_arr as $u_parts) {
            $customer_used_spare_parts_total += $u_parts['quantity'] * $u_parts['price'];
        }
        $job_card_calc = JobCardsCalculation::where(['job_id' => $job_id])->first();
        $main_arr = array();
        if ($job_card_calc) { // Update
            $job_card_calc->used_spare_parts_total = round($customer_used_spare_parts_total, 3);
            $grand_total = $customer_used_spare_parts_total + $job_card_calc->new_spare_parts_total + $job_card_calc->labours_total;
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
            $job_card_calc->used_spare_parts_total = round($customer_used_spare_parts_total, 3);
            $job_card_calc->new_spare_parts_total = 0.00;
            $job_card_calc->labours_total = 0.00;
            
            $job_card_calc->grand_total = round($customer_used_spare_parts_total, 3);
            $job_card_calc->balance =round($customer_used_spare_parts_total, 3);
            //print_r($job_card_calc);
            
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

    
}
