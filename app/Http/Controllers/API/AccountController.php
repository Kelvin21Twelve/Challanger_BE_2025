<?php

namespace App\Http\Controllers\API;

use App\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        $user_id = $this->request->user()->id;
        $account = new Account();
        $account->fill($request->all());
        $account->user_id = $user_id;
        $account->save();
        return response()->json(['success' => true, 'data' => $account]);
    }

    public function update(Request $request, $id) {
        $account = Account::find($id);
        if ($account) {
            $account->fill($request->all());
            $account->printable = ($request->input("printable")) ? 1 : 0;
            $account->is_bank_account = ($request->input("is_bank_account")) ? 1 : 0;
            $account->is_cash_account = ($request->input("is_cash_account")) ? 1 : 0;
            $account->save();
            return response()->json(['success' => true, 'data' => $account]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    public function search_account(Request $request) {
               $account_code = $request->input("account_code");
               if($account_code) {
                    $query = Account::where('account_code', '=', $account_code)->get();
                    if($query){
                                return response()->json(['success' => true, 'data' => $query]);
                    }else {
                            return response()->json(['success' => false, 'data' => ""]);
                    }
                }
    }

    public function check_account_exist(Request $request){
        $acc_code=@$request['acc_code']; $acc_code_id='0';
        if($acc_code){
           $acc_code=Account::where('account_code','=',$acc_code)->get();
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
