<?php

namespace App\Http\Controllers\API;
use App\Account;
use App\Transaction;
use App\GeneralLedger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function gnrl_ledgrinsert(Request $request) {

        if(!empty($request['from_acc_no']) && !empty($request['to_acc_no']) && !empty($request['date']) && !empty($request['type']) && !empty($request['description']) && !empty($request['from_acc_name']) && !empty($request['to_acc_name']) && !empty($request['amount'])  ){
            $transaction = new Transaction();
            $transaction->from_acc_no = $request['from_acc_no'];
            $transaction->to_acc_no = $request['to_acc_no'];
            $transaction->date = $request['date'];
            $transaction->type = $request['type'];
            $transaction->description = $request['description'];
            $transaction->from_acc_name = $request['from_acc_name'];
            $transaction->to_acc_name = $request['to_acc_name'];
            $transaction->amount = $request['amount'];
            $transaction->save();

            $data = array(
                            "data1" => array(
                                "transaction_id" => $transaction->id,
                                "acc_no" =>  $transaction->from_acc_no,
                                "date" => $transaction->date,
                                "credit" => "0",
                                "debit" => $transaction->amount,
                                "type" => $transaction->type,
                                "description" =>$transaction->description,
                                "created_at" =>date('Y-m-d H:i:s'),
                                "updated_at" =>date('Y-m-d H:i:s')

                            ),
                            "data2" => array(
                                "transaction_id" => $transaction->id,
                                "acc_no" =>  $transaction->to_acc_no,
                                "date" => $transaction->date,
                                "credit" =>$transaction->amount,
                                "debit" => '0',
                                "type" => $transaction->type,
                                "description" =>$transaction->description,
                                "created_at" =>date('Y-m-d H:i:s'),
                                "updated_at" =>date('Y-m-d H:i:s')
                            )
        );

        $ledger= GeneralLedger::insert($data);

        if($ledger && $transaction){
            $amt = $request['amount'];
            $modelAccount = Account::where("account_code",$request['to_acc_no'])->increment('balance',$amt);
            $modelAccount = Account::where("account_code",$request['from_acc_no'])->decrement('balance',$amt);
            // $modelAccount->balance += $request['amount'];
            // $modelAccount->save();

            return response()->json(['success' => true, 'data' => $transaction]);
        }else{
               return response()->json(['success' => false, 'data' => ""]);
        }

        }
    }

    public function gnrl_ledgredit(Request $request){

       if(!empty($request['from_acc_no']) && !empty($request['to_acc_no']) && !empty($request['date']) && !empty($request['type']) && !empty($request['description']) && !empty($request['from_acc_name']) && !empty($request['to_acc_name']) && !empty($request['amount']) && !empty($request['id']) ){

            $UpdateTransaction = Transaction::where('id',$request['id'])->update(['from_acc_no' => $request['from_acc_no'],'to_acc_no' => $request['to_acc_no'],'date' => $request['date'],'amount' => $request['amount'],'from_acc_name' => $request['from_acc_name'],'to_acc_name' => $request['to_acc_name'],'type' => $request['type'],'description' => $request['description']]);

            if($UpdateTransaction){

                $UpdateGeneralLedger_id = GeneralLedger::where('transaction_id',$request['id'])->get();
                if($UpdateGeneralLedger_id){
                    $id1=$UpdateGeneralLedger_id[0]['id'];
                    $id2=$UpdateGeneralLedger_id[1]['id'];
                    $where1 = ['id' => $id1,'transaction_id' => $request['id']];
                    $UpdateGeneralLedger1 = GeneralLedger::where($where1)->update(
                        ['acc_no' => $request['from_acc_no'],
                         'date' => $request['date'],
                         'credit' => '0',
                         'debit' => $request['amount'],
                         'type' => $request['type'],
                         'description' => $request['description'],
                         'updated_at' =>date('Y-m-d H:i:s')
                        ]);
                    $where2 = ['id' => $id2,'transaction_id' => $request['id']];
                    $UpdateGeneralLedger2 = GeneralLedger::where($where2)->update(
                        ['acc_no' => $request['from_acc_no'],
                         'date' => $request['date'],
                         'credit' => $request['amount'],
                         'debit' => '0',
                         'type' => $request['type'],
                         'description' => $request['description'],
                         'updated_at' =>date('Y-m-d H:i:s')]);
                    if($UpdateGeneralLedger1 &&  $UpdateGeneralLedger2){
                         return response()->json(['success' => true, 'data' => ""]);
                    }else{
                           return response()->json(['success' => false, 'data' => ""]);
                    }

                }
            }

       }
    }

    public function gnrl_ledgrsrch(Request $request) {

        $from_dt = $request["from_date"];
        $to_dt = $request["to_date"];
        $data =  GeneralLedger::whereDate('created_at', '>=', $from_dt)->whereDate('created_at', '<=', $to_dt)->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function gnrl_ledget(Request $request) {

        $id=$request['id'];
        $query = Transaction::where(['id' => $id])->get();
            if($query){
                        return response()->json(['success' => true, 'data' => $query]);
            }else {
                    return response()->json(['success' => false, 'data' => ""]);
            }

    }

    public function gnrl_ledgrdel(Request $request){
        $id=$request['id'];
        $Transaction= Transaction::where('id', $id);
        $GeneralLedger= GeneralLedger::where('transaction_id', $id);

        if($Transaction && $GeneralLedger){
            $Transaction->delete();
            $GeneralLedger->delete();
                        return response()->json(['success' => true, 'data' => '']);
            }else {
                    return response()->json(['success' => false, 'data' => ""]);
            }
    }



}
