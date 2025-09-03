<?php

namespace App\Http\Controllers\API;
use App\NewSpareParts;
use App\UsedSpareParts;
use App\SparePartsReturn;
use App\CustomersNewSpareParts;
use App\CabNo;
use App\JobCard;
use App\CustomersUsedSpareParts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SparePartReturnController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

   public function search_spare_part_soled(Request $request) {
            $new_spare_parts_dtl='';
            $used_spare_parts_dtl='';
            // $cust_name = $request->input("cust_name");
            // $Phone = $request->input("Phone");
            $InvNo = $request->input("InvNo");
            // $cab_num = $request->input("cab_num");
            $from_dt = $request->input("date1");
            $to_dt = $request->input("date2");
            $new_spare_parts =  CustomersNewSpareParts::where('is_returned', '=', 0);
            $used_spare_parts = CustomersUsedSpareParts::where('is_returned', '=', 0);
            if($InvNo){
               $new_spare_parts->where('job_id', '=', $InvNo);
               $used_spare_parts->where('job_id', '=', $InvNo);
               // return response()->json(['success' => true,'new_spare_parts_dtl' => $new_spare_parts_dtl,'used_spare_parts_dtl' => $used_spare_parts_dtl]);
            }

            if($from_dt && $to_dt){

              $new_spare_parts->whereDate('created_at','>=',$from_dt)->whereDate('created_at','<=',$to_dt)->where('is_returned', '=', 0);
              $used_spare_parts->whereDate('created_at','>=',$from_dt)->whereDate('created_at','<=',$to_dt)->where('is_returned', '=', 0);
            }
            $new_spare_parts= $new_spare_parts->get();
            $used_spare_parts= $used_spare_parts->get();

            return response()->json(['success' => true, 'new_spare_parts' => $new_spare_parts,'used_spare_parts' => $used_spare_parts]);
    }

    public function search_spare_part_returned(Request $request) {
            $InvNo = $request->input("InvNo");
            $date1 = $request->input("date1");
            $date2 = $request->input("date2");
            $query = SparePartsReturn::orderBy('id','desc');
            if($InvNo){
                    $query->where('inv_no', '=',$InvNo);
            }

            if($date1 && $date2){
                $query->whereDate('date','>=',$date1)->whereDate('date','<=',$date2);
            }

            $query=$query->get();
            if($query){
                        return response()->json(['success' => true, 'data' => $query]);
            }else {
                    return response()->json(['success' => false, 'data' => ""]);
            }
    }

    public function return_spare_part_store(Request $request) {

           $job_id= $_POST['job_id'];
           $ReturnData= $_POST['spare_parts_return'];
            if(!empty($ReturnData)){
                foreach ($ReturnData as $key1 => $value1) {
                        $spare_parts_retn = new SparePartsReturn();
                        $spare_parts_retn->item_code = $value1['item_code'];
                        $spare_parts_retn->item = $value1['item'];
                        $spare_parts_retn->quantity = $value1['quantity'];
                        $spare_parts_retn->Price = $value1['Price'];
                        $spare_parts_retn->Discount = $value1['Discount'];
                        $spare_parts_retn->Total = $value1['Total'];
                        $spare_parts_retn->job_id = $job_id;
                        $spare_parts_retn->date = date('Y-m-d');
                        $spare_parts_retn->save();
                        // update return 
                        $update_UsedSpareParts = CustomersUsedSpareParts::where('item_id', '=',$value1['item_code'])->update(['is_returned' =>'1']);
                        $update_NewSpareParts =  CustomersNewSpareParts::where('item_code', '=',$value1['item_code'])->update(['is_returned' =>'1']);
                        // update balance
                        $UsedSpareParts = UsedSpareParts::where('id', '=',$value1['item_code'])->get(['balance']);
                        $NewSpareParts =  NewSpareParts::where('item_code', '=',$value1['item_code'])->get(['balance']);
                        if($NewSpareParts){
                            foreach ($NewSpareParts as $key => $value) {
                                $new_balance=$value['balance']+$value1['quantity'];
                                $new_Updatebalance = NewSpareParts::where('item_code',   '=',$value1['item_code'])->update(['balance' => $new_balance]);
                            }

                        }
                        if($UsedSpareParts){
                             foreach ($UsedSpareParts as $key => $value) {
                                 $used_balance=$value['balance']+$value1['quantity'];
                                 $usedUpdatebalance = UsedSpareParts::where('id', '=',$value1['item_code'])->update(['balance' => $used_balance]);
                             }

                        }
                }
            }


            return response()->json(['success' => true]);

    }

    public function return_spare_part_to_add(Request $request) {
        $job_id=$_POST['inv_no'];
        $id=$_POST['id'];
        $new_spare_parts =  CustomersNewSpareParts::where(['job_id' => $job_id],['id' => $id],['is_returned' =>'1'])->get();
        $used_spare_parts = CustomersUsedSpareParts::where(['job_id' => $job_id],['id' => $id],['is_returned' =>'1'])->get();

        //$new_spare_parts =  CustomersNewSpareParts::where(['job_id' => $job_id],['id' => $id])->update(['is_returned' =>'1']);
        //$used_spare_parts = CustomersUsedSpareParts::where(['job_id' => $job_id],['id' => $id])->update(['is_returned' =>'1']);

        $JobCard = JobCard::where(['id' => $job_id])->get();
        return response()->json(['success' => true, 'new_spare_parts' => $new_spare_parts,'used_spare_parts' => $used_spare_parts,'JobCard' => $JobCard]);
    }

    public function spare_get_cabs(Request $request) {

        return $cabs = CabNo::where('job_id', "!=", 0)->orderBy("cab_no", "ASC")->get();

    }



}
