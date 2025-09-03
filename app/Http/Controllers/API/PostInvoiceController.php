<?php

namespace App\Http\Controllers\API;

use App\JobCard;  
use App\NewSparePurchase;
use App\CustomersUsedSpareParts;
use App\CustomersNewSpareParts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostInvoiceController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function post_inv_srch(Request $request){
        
        if(!empty($request['from_date']) && !empty($request['to_date']) && !empty($request['type'])){
            
            switch ($request['type']) {
                case "Job_Cards":
                    
                    $JobCard =  JobCard::whereDate('created_at', '>=', $request['from_date'])->whereDate('created_at', '<=', $request['to_date'])->get();
                    
                    return response()->json(['success' => true, 'JobCard' => $JobCard]);
                    break;
                case "Purchase":
                   
                   $NewSparePurchase =  NewSparePurchase::whereDate('created_at', '>=', $request['from_date'])->whereDate('created_at', '<=', $request['to_date'])->get();

                   return response()->json(['success' => true, 'NewSparePurchase' => $NewSparePurchase]);
                    break;
                case "Sales":
                    
                    $CustomersUsedSpareParts =  CustomersUsedSpareParts::whereDate('created_at', '>=', $request['from_date'])->whereDate('created_at', '<=', $request['to_date'])->get();
                    $CustomersNewSpareParts =  CustomersNewSpareParts::whereDate('created_at', '>=', $request['from_date'])->whereDate('created_at', '<=', $request['to_date'])->get();

                     return response()->json(['success' => true, 'CustomersUsedSpareParts' => $CustomersUsedSpareParts, 'CustomersNewSpareParts' => $CustomersNewSpareParts]);
                    break;
                case "All":
                    
                    $JobCard =  JobCard::whereDate('created_at', '>=', $request['from_date'])->whereDate('created_at', '<=', $request['to_date'])->get();
                    $NewSparePurchase =  NewSparePurchase::whereDate('created_at', '>=', $request['from_date'])->whereDate('created_at', '<=', $request['to_date'])->get();
                    $CustomersUsedSpareParts =  CustomersUsedSpareParts::whereDate('created_at', '>=', $request['from_date'])->whereDate('created_at', '<=', $request['to_date'])->get();
                    $CustomersNewSpareParts =  CustomersNewSpareParts::whereDate('created_at', '>=', $request['from_date'])->whereDate('created_at', '<=', $request['to_date'])->get();

                     return response()->json(['success' => true, 'NewSparePurchase' => $NewSparePurchase, 'CustomersUsedSpareParts' => $CustomersUsedSpareParts, 'CustomersNewSpareParts' => $CustomersNewSpareParts,'JobCard' => $JobCard]);
                    break;    
                default:
                    echo "No data";
            }
        }
    }
}
