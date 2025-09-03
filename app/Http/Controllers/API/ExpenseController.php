<?php

namespace App\Http\Controllers\API;

use App\Expense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpenseController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $expense = new Expense();
        $expense->fill($request->all());
        $expense->user_id = $user_id;
        $expense->save();
        return response()->json(['success' => true, 'data' => $expense]);
    }

    public function update(Request $request, $id) {
        $expense = Expense::find($id);
        if ($expense) {
            $expense->fill($request->all());            
            $expense->save();
            return response()->json(['success' => true, 'data' => $expense]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    public function search_expense(Request $request) {
               $expense_code = $request->input("expense_code");
               if($expense_code) {
                    $query = Expense::where('expense_code', '=', $expense_code)->get();
                    if($query){
                                return response()->json(['success' => true, 'data' => $query]);
                    }else {
                            return response()->json(['success' => false, 'data' => ""]);
                    }
                }
    }

    public function check_expense_exist(Request $request){
        $acc_code=@$request['acc_code']; $acc_code_id='0';
        if($acc_code){
           $acc_code=Expense::where('expense_code','=',$acc_code)->get();
           if($acc_code){
            foreach ($acc_code as $key => $value) {
                     $acc_code_id=$value['id'];

            }
            if($acc_code_id!='0'){
                return response()->json(['success' => true, 'data' => $acc_code_id]);
            }else{
                    return response()->json(['success' => false, 'data' => '0']);
            }

           }
        }
    }



}
