<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Role;
use App\CabNo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserController extends Controller {

    public $successStatus = 200;
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        //echo bcrypt(123456);exit;
        // echo"zdfsfdsfsdfdsfsd";exit;
        // echo"<pre>";print_r($request->all());exit;
        if (Auth::attempt(['email' => request('email'), 'password' => request('password'),'is_active' =>'0'])) {
            $user = Auth::user();
            // echo"<pre>";print_r($user);exit;
            $remember_me = (request('remember_me') ? request('remember_me') : "0");
            $refresh = request('refresh');
            $role_id = $user->department;
            // $base_url="http://21twelveinteractive.cf/challenger/api/";
            $base_url="http://127.0.0.1:8000/api/";
            // $excel_url="http://".request('ip_address')."/excel/newsparepartsample.xlsx";
            // $uploads_url="http://".request('ip_address')."/uploads/";
            //echo $base_url; echo $excel_url; echo $uploads_url; die();
            // if(request('ip_address')){
            //     $store_ip=User::where('email',request('email'))->update(["ip_address"=>request('ip_address'),"base_url"=>$base_url,"excel_url"=>$excel_url,"uploads_url"=>$uploads_url]);
            // }
            $permission = Role::where(['id' => $role_id])->first();
            //  echo"<pre>";print_r($permission);exit;
            $permission_slug = ($permission) ? $permission->permission_slug : "";
            $success['token'] = $user->createToken('Challenger')->accessToken;
            $success['user_id'] = $user->id;
            $success['permission_slug'] = $permission_slug;
            $success['remember_me'] = $remember_me;
            $success['refresh'] = $refresh;
            $success['base_url'] = $base_url;
            // $success['excel_url'] = $excel_url;
            // $success['uploads_url'] = $uploads_url;
            $success['ip_address'] = request('ip_address');
            // echo"<pre>";print_r($success);exit;
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            return response()->json(['success' => false, 'error' => 'Unauthorised', 'msg' => "Incorrect Email or Password"], 200);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $active="1";
        //echo "<pre>"; print_r($request->all()); 
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|email',
                    'password' => 'required',
                    'Confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()], 200);
        }
        if($request['email']){
            $user_chk = User::where(['email'=>$request['email']],['is_active'=>1])->count();
            if($user_chk > 0){
                return response()->json(['success' => false, 'error' => 'Unauthorised', 'msg' => "Email already Exist"], 200);

            }else{
                $user = Auth::User();
                $input = $request->all();
                $input['password'] = bcrypt($input['password']);
                $user_id = $this->request->user()->id;
                $input['user_id'] = $user_id;
                $role = Role::where('id', '=', $input['department'])->first();
                $user_create = User::create($input);
                $user_create->roles()->attach($role);
                $success['token'] = $user_create->createToken('MyApp')->accessToken;
                $success['name'] = $user_create->name;
                $success['id'] = $user_create->id;
                $success['is_active'] = $request['is_active'];
                return response()->json(['success' => $success], $this->successStatus);

            }
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                    'password' => 'required',
                    'Confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()], 200);
        }
        $user = User::find($id);
        if ($user) {
            $input = $request->all();
            $role = Role::where('id', '=', $input['department'])->first();
            $input['password'] = bcrypt($input['password']);
            $input['is_active'] = isset($input['is_active']) ? $input['is_active'] : '0';
            $user->update($input);
            $user->roles()->sync($role);
            return response()->json(['success' => true, 'data' => $user]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details() {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    protected function validator_user_password(array $data) {
        $messages = [
            'user_old_password.required' => 'Old password is required',
            'user_new_password.required' => 'New password is required',
            'user_confirm_password.required' => 'Confirm password is required',
            'user_new_password.min' => 'New password must be 6 character long',
        ];
        return Validator::make($data, [
                    'user_old_password' => 'required',
                    'user_new_password' => 'required|string|min:6',
                    'user_confirm_password' => 'required|string|same:user_new_password',
                        ], $messages);
    }

    public function change_password(Request $request) {
        try {
            $validator_user_password = $this->validator_user_password($request->all());
            if ($validator_user_password->fails()) {
                return response()->json(['success' => FALSE, 'errors' => $validator_user_password->getMessageBag()->toArray()]);
            } else {
                $logged_id = $this->request->user()->id;
                $old_password = $request->input('user_old_password');
                $new_password = $request->input('user_new_password');
                $user = User::find($logged_id);
                if (!Hash::check($old_password, $user->password)) {
                    return response()->json(['success' => FALSE, 'errors' => ['user_old_password' => array('Old password does not match.')]]);
                } else {
                    $password = Hash::make($new_password);
                    $user->password = $password;
                    $user->save();
                    return response()->json(['success' => true, 'msg' => 'Password Updated Successfully']);
                }
            }
        } catch (Exception $ex) {
            return response()->json(['success' => false, 'msg' => 'something went wrong']);
        }
    }

    public function check_visa_expiry_details(){
        $user_id=$_POST['user_id'];
        if($user_id){
            $visa_date=User::where('id','=',$user_id)->get(['visa_end']);
            if($visa_date){
                $end_date=$visa_date['0']['visa_end'];
                $days_ago = date('Y/m/d', strtotime('-2 days', strtotime($end_date)));
                $todays_date=date("Y/m/d");
                if($todays_date >= $days_ago ){
                       //echo "greater or equal";
                        return response()->json(['success' => true, 'data' => '1']);
                }else{
                         //echo "smaller";
                        return response()->json(['success' => false, 'data' => '0']);
                }
            }
        }
    }

    /*public function check_unique_code_exist(){
        $unique_code=$_POST['unique_code']; $unique_code_id='0';
        if($unique_code){
            $check_unique_code=User::where('unique_code','=',$unique_code)->get();
           if($check_unique_code){
            foreach ($check_unique_code as $key => $value) {
                     $unique_code_id=$value['id'];

            }
            if($unique_code_id!='0'){
                return response()->json(['success' => true, 'data' => $unique_code_id]);
            }else{
                    return response()->json(['success' => false, 'data' => '0']);
            }

           }
        }
    }*/

      public function user_store(Request $request) {
        $validator_user_info = $this->validator_user_info($request->all());
        if ($validator_user_info->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_user_info->getMessageBag()->toArray()]);
        } else {
            $user = Auth::user();
            if (!empty($_POST['employee_id'])) {
                $user_id = $_POST['employee_id'];
                $dob = date('Y-m-d', strtotime($request["dob"]));
                $join_date = date('Y-m-d', strtotime($request["join_date"]));
                $visa_start = date('Y-m-d', strtotime($request["visa_start"]));
                $visa_end = date('Y-m-d', strtotime($request["visa_end"]));
                $pass_start = date('Y-m-d', strtotime($request["pass_start"]));
                $pass_end = date('Y-m-d', strtotime($request["pass_end"]));
                $user_data = User::findorfail($user_id);
                $user_data->name = $request['name'];
                $user_data->department = $request['department'];
                $user_data->civil_id = $request['civil_id'];
                $user_data->job_title = $request['job_title'];
                $user_data->nationality = $request['nationality'];
                $user_data->salary = $request['salary'];
                $user_data->visa_type = $request['visa_type'];
                $user_data->pass_no = $request['pass_no'];
                $user_data->dob = $dob;
                $user_data->join_date = $join_date;
                $user_data->visa_start = $visa_start;
                $user_data->visa_end = $visa_end;
                $user_data->pass_start = $pass_start;
                $user_data->pass_end = $pass_end;
                $user_data->email = $request['email'];
                $user_data->type = $request['user_type'];
                if ($user_data->update()) {
                    return response()->json(['success' => TRUE, 'data' => $user_data, 'op' => 'update', 'msg_type' => 'success', 'msg' => 'Employee has been updated successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'update', 'msg_type' => 'error', 'msg' => 'Employee Updation failed!']);
                }
            } else {
                $manager_role = Role::where('slug', 'employee')->first();
                $unique_code = $request['unique_code'];
                $dob = date('Y-m-d', strtotime($request["dob"]));
                $join_date = date('Y-m-d', strtotime($request["join_date"]));
                $visa_start = date('Y-m-d', strtotime($request["visa_start"]));
                $visa_end = date('Y-m-d', strtotime($request["visa_end"]));
                $pass_start = date('Y-m-d', strtotime($request["pass_start"]));
                $pass_end = date('Y-m-d', strtotime($request["pass_end"]));
                $user_data = new User();
                $user_data->user_id = $user->id;
                $user_data->name = $request['name'];
                $user_data->department = $request['department'];
                $user_data->civil_id = $request['civil_id'];
                $user_data->job_title = $request['job_title'];
                $user_data->nationality = $request['nationality'];
                $user_data->salary = $request['salary'];
                $user_data->visa_type = $request['visa_type'];
                $user_data->pass_no = $request['pass_no'];
                $user_data->dob = $dob;
                $user_data->join_date = $join_date;
                $user_data->visa_start = $visa_start;
                $user_data->visa_end = $visa_end;
                $user_data->pass_start = $pass_start;
                $user_data->pass_end = $pass_end;
                $user_data->unique_code = $unique_code;
                $user_data->type = 'other';
                $password = $request['password'];
                $user_data->email = $request['email'];
                $user_data->type = $request['user_type'];
                $user_data->password = bcrypt($password);
                if ($user_data->save()) {
                    $user_data->roles()->attach($manager_role);
                    return response()->json(['success' => TRUE, 'data' => $user_data, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Employee has been added successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'create', 'msg_type' => 'error', 'msg' => 'Employee Insertion failed!']);
                }
            }
        }
    }

    /**
     * CLEAR INVENTORY
     */

     public function clear_inventory(Request $request){
        $id = "ITEM-1001";
        //print_r($request);exit;
        $data =DB::table('new_spare_parts')
                    ->leftJoin('new_spare_purchase','new_spare_parts.item_code', '=','new_spare_purchase.item_code')
                    ->where('new_spare_parts.item_code', $id); 

        DB::table('new_spare_purchase')->where('item_code', $id)->delete();                           
        $data->get();
        //return true;
         return response()->json(['success' => TRUE, 'data' => '', 'msg' => 'Inventory is cleared!']);
        
     }

    /**
     * CLEAR clear_all_tables
     */

    public function clear_all_tables(Request $request){
        
        $tableNames = DB::select('SHOW TABLES');
        //print_r($tableNames);exit;
        foreach ($tableNames as $name) {
            //print_r($name->Tables_in_challenger_new);exit;
            //if you don't want to truncate migrations
            $tbl_arr = array("expense_type","job_titles","engine","agencies","oauth_clients","oauth_personal_access_clients","permissions","roles","roles_permissions","users","users_roles","gallery_img","nationalities","oauth_access_tokens","oauth_auth_codes","brands","car_colors","car_makes","car_models","cab_nos");
            if (in_array($name->Tables_in_challenger_new,$tbl_arr)) {
                continue;
            }
            DB::table($name->Tables_in_challenger_new)->truncate();
            CabNo::where('job_id','<>','0')->update(["job_id"=> '0','job_status' => '0']);
            if($name->Tables_in_challenger_new == "memos")
            {
                DB::table($name->Tables_in_challenger_new)->truncate();
                return response()->json(['success' => TRUE, 'data' => '', 'msg' => 'All table clear successfully!']);

            }
            
        }
     }
     
    public function get_logo(){
        $image="http://127.0.0.1/challenger-app/public/challenger2-1.png";
        // echo "<pre>"; print_r($image); exit;
        // $image = public_path('/image/challenger2-1.png');
        // $logo = base64_encode($image);
            return response()->json(['success' => TRUE, 
            'data' => $image,
            // 'http://127.0.0.1/challenger2-1.png'
        ]);
        // if(file_exists($logo)){
        // }else{
        //     return response()->json(['success' => false, 'data' => '']);
        // }
    } 

}
