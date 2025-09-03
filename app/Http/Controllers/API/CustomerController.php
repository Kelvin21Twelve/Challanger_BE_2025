<?php

namespace App\Http\Controllers\API;

use App\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        $rules = array(
            'phone' => [
                'required',
                'numeric',
                'digits_between:5,15',
                Rule::unique('customers')->where(function ($query) {
                            $query->where('is_delete', 0);
                        })
            ],
        );
        //$rules = array(
        //    'phone' => 'required|numeric|digits_between:5,15|unique:customers',
        //);
        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'server_errors' => $validator->errors()]);
        } else {
            $user_id = $this->request->user()->id;
            $customers = new Customer();
            $customers->fill($params);
            $customers->user_id = $user_id;
            $customers->save();
            return response()->json(['success' => true, 'data' => $customers]);
        }
    }

    public function update(Request $request, $id) {
        $rules = array(
            'phone' => [
                'required',
                'numeric',
                'digits_between:5,15',
                Rule::unique('customers')->where(function ($query) {
                            $query->where([['is_delete', '=', 0]]);
                        })->ignore($request->id),
            ],
        );
        $params = $request->all();
        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'server_errors' => $validator->errors()]);
        } else {
            $customers = Customer::find($id);
            if ($customers) {
                $customers->update($request->all());
                return response()->json(['success' => true, 'data' => $customers]);
            } else {
                return response()->json(['success' => false, 'data' => ""]);
            }
        }
    }

}
