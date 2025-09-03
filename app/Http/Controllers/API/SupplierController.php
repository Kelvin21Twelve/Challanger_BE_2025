<?php

namespace App\Http\Controllers\API;

use App\Supplier;
use App\SupplierPayment;
use App\NewSparePurchase;       
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupplierController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        return Supplier::all();
    }

    public function show($id) {
        $Supplier = Supplier::find($id);
        return response()->json(['success' => true, 'data' => $Supplier]);
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $Supplier = new Supplier();
        $Supplier->fill($request->all());
        $Supplier->user_id = $user_id;
        $Supplier->save();
        return response()->json(['success' => true, 'data' => $Supplier]);
    }

    public function update(Request $request, $id) {
        $Supplier = Supplier::find($id);
        if ($Supplier) {
            $Supplier->update($request->all());
            return response()->json(['success' => true, 'data' => $Supplier]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    } 

    // public function check_supplier_exist(){
    //     $name=$_GET['name']; $name_id='0';
    //     if($name){
    //        $check_name=Supplier::where([['name','=',$name],['is_delete','=','0']])->get(); 
    //        if($check_name){
    //         foreach ($check_name as $key => $value) {
    //                  $name_id=$value['id'];
                     
    //         }
    //         if($name_id!='0'){
    //             return response()->json(['success' => true, 'data' => $name_id]); 
    //         }else{
    //                 return response()->json(['success' => false, 'data' => '0']); 
    //         }
            
    //        }
    //     }
    // }

     public function get_supplier(){
        $name=Supplier::where('is_delete','=','0')->get(); 
        if($name){
               return response()->json(['success' => true, 'data' => $name]); 
        }else{
                    return response()->json(['success' => false, 'data' => '0']); 
        }
            
        }
       

    public function add_payment_acc(){
        //print_r($request->All());
        $payment_mode=$_POST['payment_mode'];
        $amount=$_POST['amount'];
        $cheque_no=$_POST['check_no'];
        $user_id=$_POST['user_id'];
        $payment_id=$_POST['payment_id'];
        $total_amount=$_POST['total_amount'];
        $payment_supplier=$_POST['payment_supplier'];
        $invoice_no=$_POST['invoice_no'];
        if($payment_mode && $amount  && $user_id ){
            $supplier_payment_result= new SupplierPayment();
            $supplier_payment_result->name =$payment_supplier;
            $supplier_payment_result->payment_mode= $payment_mode;
            $supplier_payment_result->amount=$amount;
            $supplier_payment_result->cheque_no=$cheque_no;
            $supplier_payment_result->user_id=$user_id;
            $supplier_payment_result->invoice_no=$invoice_no;
            if($supplier_payment_result->save()){
                $balance_price=$total_amount - $amount;
                if($balance_price ==0){
                    $update=NewSparePurchase::where('id',$payment_id)->
                    update(["status"=>"done","remaining_amt"=>$balance_price]);
                 }else{
                    $update=NewSparePurchase::where('id',$payment_id)->
                    update(["status"=>"pending","remaining_amt"=>$balance_price,"remaining_amt"=>$balance_price]);
                 }
                 return response()->json(['success' => true, 'data' => $supplier_payment_result]);
            }else{
                return response()->json(['success' => false, 'data' => '0']); 
            } 
        }
    }  

    public function get_supplier_payment_acc_deatils(){
        $supplier_name=$_POST['supplier_name'];
        if($supplier_name){
           $supplier_details=SupplierPayment::where([['is_delete','=','0'],['name','=',$supplier_name]])->get(); 
          /* print_r($supplier_details); die();*/
           if($supplier_details){
              return response()->json(['success' => true, 'data' => $supplier_details]);
           }else{
                  return response()->json(['success' => false, 'data' => '0']);
           }  
        }
    } 
}

    

