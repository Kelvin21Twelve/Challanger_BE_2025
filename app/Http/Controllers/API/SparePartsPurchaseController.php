<?php

namespace App\Http\Controllers\API;

use App\Supplier;
use App\NewSparePurchase;
use App\NewSpareParts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SparePartsReturn;
use App\Notification;
use Illuminate\Support\Facades\Validator;

class SparePartsPurchaseController extends Controller
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function add_spare_purcahse_data(Request $request)
    {
        //print_r($request->all()); die();
        $t_balance = '';
        $purcahse = '';
        $Updatebalance = '';
        $Updateinv_no = '';
        $ids = array();
        $balance = '';
        if ($request->input("item_data")) {
            // check if invoice exist old method
            //$invoice_no=intval(ltrim($request->input("inv_no"), '0'));
            //end
            $invoice_no = $request->input("inv_no");
            $check_if_exist_inv_no = NewSparePurchase::where('inv_no', $invoice_no)->count();

            if ($check_if_exist_inv_no) {
                return response()->json(['success' => false, 'msg' => 'Invoice no already exist', 'data' => '']);
            }
            $item_data = $request->input("item_data");
            $array = array_values(array_filter($item_data));
            foreach ($item_data as $key => $value) {
                $a_bal = $value[2];
                //echo $value[0]; die();
                $purcahse = new NewSparePurchase();
                $purcahse->date = $request->input("date");
                $purcahse->inv_type = $request->input("inv_type");
                $purcahse->inv_no = $invoice_no;
                $purcahse->supplier_name = $request->input("supplier_name");
                if ($request->input("notes")) {
                    $purcahse->note = $request->input("notes");
                }
                $purcahse->item_code = $value[0];
                $purcahse->item_name = $value[1];
                $purcahse->purchase_qty = $value[3];
                $purcahse->total_amt = $value[4];
                $purcahse->save();
                array_push($ids, $purcahse->id);
                $new_spr_bal =  NewSpareParts::where('item_code', '=', $value[0])->get();
                if ($new_spr_bal) {
                    foreach ($new_spr_bal as $key => $value_spare) {
                        $t_balance = $value_spare['balance'];
                        //if(is_numeric($a_bal)){
                        $balance = (int)$t_balance + (int)($a_bal);
                        //}
                    }


                    // if(!empty($ids)){
                    //  $Updateinv_no = NewSparePurchase::where('id', '=',$purcahse->id)->update(['inv_no' => reset($ids)]);
                    // }
                    $Updatebalance = NewSpareParts::where('item_code', '=', $value[0])->update(['balance' => $balance]);
                } else {
                    return response()->json(['success' => true, 'msg' => 'no spare parts found', 'data' => '']);
                }
            }

            if (!empty($purcahse) && !empty($Updatebalance)) {
                return response()->json(['success' => true, 'msg' => 'Added Successfully', 'data' => $purcahse]);
            }
        } else {
            return response()->json(['success' => false, 'msg' => 'fill all details', 'data' => '']);
        }
    }

    
    public function get_view_purchase_details(Request $request)
    {
        $supplier_name = $request["supplier_name"];
        $InvNo = $request["invoice_number"];
        
        $purchased_spare_parts =  NewSparePurchase::where('is_delete', '0')->where('id', $request['id'])->orderBy('id', 'desc');
        
        if ($supplier_name) {
            $purchased_spare_parts->where('supplier_name', '=', $supplier_name);
        }
        
        if ($InvNo) {
            $purchased_spare_parts->where('inv_no', '=', $InvNo);
        }
        
        $purchased_spare_parts = $purchased_spare_parts->get();
        // echo"<pre>";print_r($purchased_spare_parts);exit;
        if ($purchased_spare_parts) {
            return response()->json(['success' => true, 'view_purchased_spare_parts' => $purchased_spare_parts]);
        } else {
            return response()->json(['success' => false, 'view_purchased_spare_parts' => '']);
        }
    }

    public function spare_part_purchased_data(Request $request)
    {
        $supplier_name = $request["supp_name"];
        $InvNo = $request["InvNo"];
        $date1 = $request["date1"];
        $date2 = $request["date2"];
        $purchased_spare_parts =  NewSparePurchase::where('is_delete', '0')->orderBy('id', 'desc');

        if ($supplier_name) {
            $purchased_spare_parts->where('supplier_name', '=', $supplier_name);
        }

        if ($InvNo) {
            $purchased_spare_parts->where('inv_no', '=', $InvNo);
        }

        if ($date1 && $date2) {
            $purchased_spare_parts->whereDate('date', '>=', $date1)->whereDate('date', '<=', $date2);
        }

        $purchased_spare_parts = $purchased_spare_parts->get();
        if ($purchased_spare_parts) {
            return response()->json(['success' => true, 'purchased_spare_parts' => $purchased_spare_parts]);
        } else {
            return response()->json(['success' => false, 'purchased_spare_parts' => '']);
        }
    }
    public function get_invoice_number(Request $request)
    {
        $supplier = $request['supplier'];
        if ($supplier) {
            //$purchased_history =  NewSparePurchase::where('supplier_name', '=',$supplier)->get();
            $invoice_num_list =  NewSparePurchase::selectRaw('id,inv_no')->where('supplier_name', '=', $supplier)->groupBy('inv_no')->get();
            // print_r($invoice_num_list);die;
            if ($invoice_num_list) {
                return response()->json(['success' => true, 'invoice_num_list' => $invoice_num_list]);
            } else {
                return response()->json(['success' => false, 'invoice_num_list' => '']);
            }
        }
    }
    public function spare_part_history_data(Request $request)
    {
        $supplier = $request['supplier'];
        if ($supplier) {
            //$purchased_history =  NewSparePurchase::where('supplier_name', '=',$supplier)->get();
            $purchased_history =  NewSparePurchase::selectRaw('id,inv_no,supplier_name,sum(purchase_qty) as total_qty,sum(total_amt) as totalAmt')->where('supplier_name', '=', $supplier)->groupBy('inv_no')->get();
            //print_r($purchased_history->toArray());die;
            if ($purchased_history) {
                return response()->json(['success' => true, 'purchased_history' => $purchased_history]);
            } else {
                return response()->json(['success' => false, 'purchased_history' => '']);
            }
        }
    }

    public function purchase_return(Request $request)
    {
        $data = $request->all();
        $datas = $data['myData'];
        $invno = $datas["InvNo"];
        $id =  $datas["id"];
        $balances =  $datas["balance"];
        $item_code =  $datas["item_code"];
        $return_quantity =  $datas["quntity"];
        $price =  $datas["price"];
        //print_r($datas); die;
        // update new spare part 
        $Updatebalance = NewSpareParts::where('item_code', '=', $item_code)->get();
        if ($Updatebalance[0]['balance'] >  $return_quantity) {
            $each = @$Updatebalance[0]['sale_price'] *  $datas["quntity"];
            $remain_bal = @$Updatebalance[0]['balance'] - $return_quantity;
            $Updatebalance = NewSpareParts::where('item_code', '=', $item_code)->update(['balance' => $remain_bal]);
        } else {
            return response()->json(['success' => false, 'data' => '0', 'msg' => 'spare part return quantity is greater than available quantity']);
        }
        $get_retunrn_data = NewSparePurchase::where([['id', '=', $id], ['inv_no', '=', $invno]])->first();
        if (!empty($get_retunrn_data)) {
            $returned_qty = $datas["quntity"];
            $balance_qty = $get_retunrn_data->purchase_qty - $datas["quntity"];
            $to_return_amt =  $each;
            if ($get_retunrn_data->balance_qty == 0) {
                $balance_qtys = $get_retunrn_data->purchase_qty - $get_retunrn_data->returned_qty;
            } else {
                $pbal = $get_retunrn_data->balance_qty + $return_quantity;
                $balance_qtys = $get_retunrn_data->purchase_qty - $pbal;
            }

            if ($get_retunrn_data->to_return_amt == 0) {
                $to_return_amts = $to_return_amt;
            } else {
                $to_return_amts = $get_retunrn_data->to_return_amt + $to_return_amt;
            }
            $Updatepurchase = NewSparePurchase::where([['id', '=', $id], ['inv_no', '=', $invno]])->update(
                [
                    'returned_qty' =>  $get_retunrn_data->returned_qty + $returned_qty,
                    'to_return_amt' => $to_return_amts
                ]
            );
            if ($Updatepurchase) {
                $get_retunrn_datas = NewSparePurchase::where([['id', '=', $id], ['inv_no', '=', $invno]])->first();
                $Updatepurchases = NewSparePurchase::where([['id', '=', $id], ['inv_no', '=', $invno]])->update(
                    ['balance_qty' => $get_retunrn_datas->purchase_qty - $get_retunrn_datas->returned_qty]
                );
                $spare_parts_retn = new SparePartsReturn();
                $spare_parts_retn->item_code = $item_code;
                $spare_parts_retn->item = $get_retunrn_datas->item_name;
                $spare_parts_retn->previous_quantity = $get_retunrn_data->returned_qty;
                $spare_parts_retn->quantity = @$return_quantity;
                $spare_parts_retn->Price = @$Updatebalance[0]['sale_price'] * $datas["quntity"];
                $spare_parts_retn->Discount = '';
                $spare_parts_retn->Total = $get_retunrn_data->purchase_qty;
                $spare_parts_retn->job_id = "";
                $spare_parts_retn->inv_no = $invno;
                $spare_parts_retn->date = date('Y-m-d');
                $spare_parts_retn->save();
            }
            return response()->json(['success' => true, 'data' => '1', 'msg' => 'Purchased Item is removed']);
        } else {
            return response()->json(['success' => false, 'data' => '0', 'msg' => 'Invoive no. not foundss']);
        }
    }


    public function order_history(Request $request)
    {
        if ($request['inv_no']) {
            $invno = $request['inv_no'];
            $order = NewSparePurchase::where('inv_no', '=', $invno)->get();
            $total_inv_nu_amount = NewSparePurchase::selectRaw('sum(total_amt) as total_amt')->groupBy('inv_no')->where('is_delete', '=', 0)->where('inv_no', '=', $invno)->get();
            // echo"pre";print_r($total_inv_nu_amount[0]['total_amt']);exit;
            $return = SparePartsReturn::where('inv_no', '=', $invno)->get();
            if ($order) {
                return response()->json(['success' => true, 'order' => $order, 'return' => $return, 'total_amt' => $total_inv_nu_amount[0]['total_amt']]);
            }
        }
    }

    public function  get_purchase_history()
    {
        $new_spare = NewSparePurchase::where('is_delete', '=', 0);
        // $new_spare = NewSparePurchase::selectRaw('id,inv_no,inv_type,item_code,supplier_name,sum(remaining_amt) as remaining_amt,sum(purchase_qty) as purchase_qty,sum(total_amt) as total_amt')->where('is_delete', '=',0)->groupBy('inv_no');
        if ($new_spare->count() > 0) {
            $new_spare = $new_spare->get();
            return response()->json(['success' => true, 'purchase_order' => $new_spare]);
        } else {
            return response()->json(['success' => true, 'purchase_order' => ""]);
        }
    }



    public function  insert_notification(Request $request)
    {
        $data = $request->all();
        $item_code = $data['item_code'];
        $item_name = $data["item_name"];

        $notidata = NewSparePurchase::where('item_code', '=', $item_code)->where("status", "=", "new")->first();
        if (empty($notidata)) {
            $noti = new Notification();
            $noti->item_code = $item_code;
            $noti->item_name = $item_name;
            $noti->status = "new";
            $noti->user_id = $this->request->user()->id;
            $noti->save();
        }

        return response()->json(['success' => true, 'notification' => array("item_code" => $item_code, "item_name" => $item_name, "status" => "new")]);
    }
}
