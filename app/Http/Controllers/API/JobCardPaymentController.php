<?php

namespace App\Http\Controllers\API;

use App\JobCardPayment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JobCardsCalculation;
use App\JobCard;
use App\CabNo;

class JobCardPaymentController extends Controller
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index()
    {
        return JobCardPayment::all();
    }

    public function show($id)
    {
        $data = JobCardPayment::find($id);
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function store(Request $request)
    {
        // print_r($request->all());exit;
        $job_status = '';
        $payment_exist_bal = '';
        $user_id = $this->request->user()->id;
        if (!empty($request['amount']) && ((!empty($request['balance'])) || ($request['balance']==0)) && !empty($request['pay_by'])) {
            // if ($request['amount'] > $request['balance']) {
            //     return response()->json(['error' => true, 'data' => 'greater_amount']);
            // } else {
                # code...
                $job_card_payment = new JobCardPayment();
                $job_card_payment->fill($request->all());
                $job_card_payment->amount = $request->amount;
                $job_card_payment->pay_by = $request->pay_by;
                $job_card_payment->auth_code = $request->auth_code;
                $job_card_payment->remaining = $request->balance;
                $job_card_payment->job_id = $request->job_id;
                $job_card_payment->user_id = $user_id;
                $job_card_payment->save();
                $job_id = $request->input("job_id");
                $amount = $request->input("amount");
                $balance = $request->input("balance");
                if (!empty($job_id)) {
                    // get job details
                    $get_job_card_details = JobCard::findOrFail($job_id);

                    // update  job details
                    if ($get_job_card_details->status != 'delivery') {
                        $job_card_details = JobCard::where(['id' => $job_id])->update(['status' => 'paid_wait']);
                        $cab_details = CabNo::where(['job_id' => $job_id])->update(['job_status' => 'paid_wait']);
                        // $job_card_details = JobCard::where(['id' => $job_id])->update(['status' => 'delivery']);
                        // $cab_details = CabNo::where(['job_id' => $job_id])->update(['job_status' => 'delivery']);
                    }
                    //get balance $amount
                    $payment_exist = JobCardsCalculation::where('job_id', $job_id)->get();
                    if ($payment_exist) {
                        foreach ($payment_exist as $key => $value) {
                            $payment_exist_bal = $value['balance'];
                        }
                    }

                    if (!empty($payment_exist_bal) && $payment_exist_bal != '0') {
                        if ($amount <= $payment_exist_bal) {
                            $remaining = ($payment_exist_bal - $amount);
                            $job_card_payment->remaining = round($remaining, 3);
                            $job_card_payment->user_id = $user_id;
                            $job_card_bal = JobCardPayment::where('id', $job_card_payment->id)->update(['remaining' => $remaining]);
                            $job_card_cal = JobCardsCalculation::where(['job_id' => $job_id])->update(['balance' => $remaining]);
                            // if($get_job_card_details->status=='delivery'){

                            //     if ($remaining == 0) {
                            //         $cab_details = CabNo::where('job_id', $job_id)->update(['job_id' => '0', 'job_status' => 'NULL']);
                            //     }
                            // }
                            return response()->json(['success' => true, 'data' => $job_card_payment]);
                        } else {
                            // echo"1";die;
                            return response()->json(['error' => true, 'data' => 'greater_amount']);
                        }
                    } else {
                        // echo"sdf";exit;
                        return response()->json(['success' => true, 'data' => $job_card_payment]);
                    }
                }
                // }
            } else {
            // echo"2";die;
            return response()->json(['error' => true, 'data' => 'greater_amount']);
        }
    }



    public function update(Request $request, $id)
    {
        $job_card_payment = JobCardPayment::find($id);
        if ($job_card_payment) {
            $job_card_payment->update($request->all());
            return response()->json(['success' => true, 'data' => $job_card_payment]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }
    public function insert_labour_discount_entry(Request $request){
        $user_id = $this->request->user()->id;
        $job_card_cal = JobCardsCalculation::where(['job_id' => $request['job_id']])->first();
        if($job_card_cal){
            $update_job_card_cal = JobCardsCalculation::where(['job_id' => $request['job_id']])->update(['balance' => $request['balance'],'grand_total'=>$request['grand_totl'],'labour_disc'=>$request['disc'],'labours_total'=>$request['lbr_disc']]);
            $job_card_payment = new JobCardPayment();
            $job_card_payment->fill($request->all());
            $job_card_payment->amount = $request['minus_count'];
            $job_card_payment->pay_by = 5;
            $job_card_payment->remaining = $request['balance'];
            $job_card_payment->job_id = $request->job_id;
            $job_card_payment->user_id = $user_id;
            $job_card_payment->save();
            return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => false]);
        }
    }
}
