<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;
use App\UsersAdditions;
use App\UsersDeductions;
use App\UsersVacations;
use App\UsersWarnings;
use App\UsersDocuments;
use App\UsersAbsences;
use App\UsersExcuses;
use App\UsersAttendances;
use App\AttendancesEntries;
use App\VacType;
use App\Vehicle;
use App\JobCard; 
use App\CarMake;
use App\JobTitle;
use DateTime;
use Mail;
use Input,
    Redirect,
    Session,
    Response,
    DB;
use Illuminate\Validation\Rule;

class UsersController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request) {
        $perPage = 1;
        $id = $request['id'];
        $users = User::orderBy('created_at', 'desc');
        if ($id) {
            $users = $users->where('id', '=', $id);
        }
        $user_data = $users->first();

        if ($user_data) {
            return response()->json(['success' => TRUE, 'op' => 'list', 'data' => $user_data, 'msg_type' => 'success', 'msg' => 'Record found!']);
        } else {
            return response()->json(['success' => FALSE, 'op' => 'list', 'msg_type' => 'error', 'msg' => 'No record found!']);
        }
    }

    protected function validator_user_info(array $data) {
        if (isset($data['employee_id']) && !empty($data['employee_id'])) {
            $user_id = $data['employee_id'];
            return Validator::make($data, [
                        'name' => 'required',
                        'department' => 'required',
                        'job_title' => 'required',
                        'join_date' => 'required',
                        'unique_code' => [
                            'required',
                            Rule::unique('users')->where(function ($query) {
                                        $query->where([['is_delete', '=', 0]]);
                                    })->ignore($user_id),
                        ],
                        //'unique_code' => 'required|unique:users,unique_code,' . $user_id,
                        'email' => 'required|email|unique:users,email,' . $user_id,
                            ], ['email.required' => 'User is already exists!!', 'unique_code.unique_code' => 'The ID has already been taken']);
        } else {
            return Validator::make($data, [
                        'name' => 'required',
                        'department' => 'required',
                        'job_title' => 'required',
                        'join_date' => 'required',
                        'unique_code' => [
                            'required',
                            Rule::unique('users')->where(function ($query) {
                                        $query->where('is_delete', 0);
                                    })
                        ],
                        //'unique_code' => 'required|string|max:255|unique:users',
                        'email' => 'required|string|email|max:255|unique:users|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
                        'password' => 'required|string|min:6'
                            ], [
                        'email.required' => 'Enter the email address in the format someone@example.com',
                        'email.email' => 'Enter the email address in the format someone@example.com',
                        'email.regex' => 'Enter the email address in the format someone@example.com',
                        'unique_code.unique' => 'The ID has already been taken',
            ]);
        }
    }

    public function user_store(Request $request) {
        $validator_user_info = $this->validator_user_info($request->all());
        if ($validator_user_info->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_user_info->getMessageBag()->toArray()]);
        } else {
            $user = Auth::user();
            if (!empty($_POST['employee_id'])) {
                $user_id = $_POST['employee_id'];
                $unique_code = $request['unique_code'];
                $dob = date('Y-m-d', strtotime($request["dob"]));
                $join_date = date('Y-m-d', strtotime($request["join_date"]));
                $visa_start = date('Y-m-d', strtotime($request["visa_start"]));
                $visa_end = date('Y-m-d', strtotime($request["visa_end"]));
                $pass_start = date('Y-m-d', strtotime($request["pass_start"]));
                $pass_end = date('Y-m-d', strtotime($request["pass_end"]));
                $user_data = User::findorfail($user_id);
                $user_data->name = $request['name'];
                $user_data->name_in_arabic = $request['ara_name'];
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
                $user_data->email = $request['email'];
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
                $user_data->name_in_arabic = $request['ara_name'];
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
                $password = $request['password'];
                $user_data->email = $request['email'];
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

    protected function validator_additions_info(array $data) {
        return Validator::make($data, [
                    'additions_type' => 'required',
                    'additions_amount' => 'required',
                    'additions_date' => 'required',
                    'additions_description' => 'required',
                    'eadditions_id' => 'required',
                        ], ['eadditions_id.required' => 'Please select user for additions!!',]
        );
    }

    public function eadditions_store(Request $request) {
        $validator_additions_info = $this->validator_additions_info($request->all());
        if ($validator_additions_info->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_additions_info->getMessageBag()->toArray()]);
        } else {
            $user = Auth::user();
            if (!empty($_POST['additions_id'])) {
                $additions_id = $_POST['additions_id'];
                $additions_date = date('Y-m-d', strtotime($request["additions_date"]));
                $additions_end_date = date('Y-m-d', strtotime($request["additions_end_date"]));
                $obj_data = UsersAdditions::findorfail($additions_id);
                $obj_data->type = $request['additions_type'];
                $obj_data->amount = $request['additions_amount'];
                $obj_data->description = $request['additions_description'];
                $obj_data->user_id = $request['eadditions_id'];
                $obj_data->start_date = $additions_date;
                $obj_data->end_date = $additions_end_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'update', 'msg_type' => 'success', 'msg' => 'Additions has been updated successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'update', 'msg_type' => 'error', 'msg' => 'Additions failed!']);
                }
            } else {
                $additions_date = date('Y-m-d', strtotime($request["additions_date"]));
                $additions_end_date = date('Y-m-d', strtotime($request["additions_end_date"]));
                $obj_data = new UsersAdditions();
                $obj_data->type = $request['additions_type'];
                $obj_data->amount = $request['additions_amount'];
                $obj_data->description = $request['additions_description'];
                $obj_data->user_id = $request['eadditions_id'];
                $obj_data->start_date = $additions_date;
                $obj_data->end_date = $additions_end_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->save()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Additions has been added successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'create', 'msg_type' => 'error', 'msg' => 'Additions Insertion failed!']);
                }
            }
        }
    }

    protected function validator_deductions_info(array $data) {
        return Validator::make($data, [
                    'deductions_type' => 'required',
                    'deductions_amount' => 'required',
                    'deductions_date' => 'required',
                    'deductions_description' => 'required',
                    'edeductions_id' => 'required',
                        ], ['edeductions_id.required' => 'Please select user for deductions!!',]
        );
    }

    public function edeductions_store(Request $request) {
        $validator_deductions_info = $this->validator_deductions_info($request->all());
        if ($validator_deductions_info->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_deductions_info->getMessageBag()->toArray()]);
        } else {
            $user = Auth::user();
            if (!empty($_POST['deductions_id'])) {
                $deductions_id = $_POST['deductions_id'];
                $deductions_date = date('Y-m-d', strtotime($request["deductions_date"]));
                $deductions_end_date = date('Y-m-d', strtotime($request["deductions_end_date"]));
                $obj_data = UsersDeductions::findorfail($deductions_id);
                $obj_data->type = $request['deductions_type'];
                $obj_data->amount = $request['deductions_amount'];
                $obj_data->description = $request['deductions_description'];
                $obj_data->user_id = $request['edeductions_id'];
                $obj_data->start_date = $deductions_date;
                $obj_data->end_date = $deductions_end_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'update', 'msg_type' => 'success', 'msg' => 'Deductions has been updated successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'update', 'msg_type' => 'error', 'msg' => 'Deductions failed!']);
                }
            } else {
                $deductions_date = date('Y-m-d', strtotime($request["deductions_date"]));
                $deductions_end_date = date('Y-m-d', strtotime($request["deductions_end_date"]));
                $obj_data = new UsersDeductions();
                $obj_data->type = $request['deductions_type'];
                $obj_data->amount = $request['deductions_amount'];
                $obj_data->description = $request['deductions_description'];
                $obj_data->user_id = $request['edeductions_id'];
                $obj_data->start_date = $deductions_date;
                $obj_data->end_date = $deductions_end_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->save()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Deductions has been added successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'create', 'msg_type' => 'error', 'msg' => 'Deductions Insertion failed!']);
                }
            }
        }
    }

    protected function validator_vacations_info(array $data) {
        return Validator::make($data, [
                    'vacations_type' => 'required',
                    'vacations_balance' => 'required',
                    'vacations_start_date' => 'required',
                    'vacations_resume_date' => 'required',
                    'vacations_end_date' => 'required',
                    'vacations_description' => 'required',
                    'evacations_id' => 'required',
                        ], ['evacations_id.required' => 'Please select user for vacations!!',]
        );
    }

    public function evacations_store(Request $request) {
        $validator_vacations_info = $this->validator_vacations_info($request->all());
        if ($validator_vacations_info->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_vacations_info->getMessageBag()->toArray()]);
        } else {
            $user = Auth::user();
            if (!empty($_POST['vacations_id'])) {
                $vacations_id = $_POST['vacations_id'];
                $vacations_start_date = date('Y-m-d', strtotime($request["vacations_start_date"]));
                $vacations_resume_date = date('Y-m-d', strtotime($request["vacations_resume_date"]));
                $vacations_end_date = date('Y-m-d', strtotime($request["vacations_end_date"]));
                $obj_data = UsersVacations::findorfail($vacations_id);
                $obj_data->type = $request['vacations_type'];
                // vacations days
                $total_leave_balance=VacType::where('id','=',$request['vacations_type'])->get(); 
                if($total_leave_balance){
                    foreach ($total_leave_balance as $key => $value) {
                           $ttl_lev=$value['vac_limit'];
                    }
                }
                $datetime1 = new DateTime($vacations_start_date);
                $datetime2 = new DateTime($vacations_end_date);
                $interval = $datetime1->diff($datetime2);
                $days_added=$interval->format('%d'); 
                $balane_levs=$ttl_lev - $days_added;
                if ($balane_levs < 0)
                {
                  $up_bal='0';
                }else{
                       $up_bal=$balane_levs;
                }
                // end of  vacations days
                $obj_data->balance = $up_bal;
                $obj_data->description = $request['vacations_description'];
                $obj_data->user_id = $request['evacations_id'];
                $obj_data->start_date = $vacations_start_date;
                $obj_data->resume_date = $vacations_resume_date;
                $obj_data->end_date = $vacations_end_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'update', 'msg_type' => 'success', 'msg' => 'Vacations has been updated successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'update', 'msg_type' => 'error', 'msg' => 'Vacations failed!']);
                }
            } else {
                $vacations_start_date = date('Y-m-d', strtotime($request["vacations_start_date"]));
                $vacations_resume_date = date('Y-m-d', strtotime($request["vacations_resume_date"]));
                $vacations_end_date = date('Y-m-d', strtotime($request["vacations_end_date"]));
                $obj_data = new UsersVacations();
                $obj_data->type = $request['vacations_type'];
                // vacations days
                $total_leave_balance=VacType::where('id','=',$request['vacations_type'])->get(); 
                if($total_leave_balance){
                    foreach ($total_leave_balance as $key => $value) {
                           $ttl_lev=$value['vac_limit'];
                    }
                }
                $datetime1 = new DateTime($vacations_start_date);
                $datetime2 = new DateTime($vacations_end_date);
                $interval = $datetime1->diff($datetime2);
                $days_added=$interval->format('%d'); 
                $balane_levs=$ttl_lev - $days_added;
                if ($balane_levs < 0)
                {
                  $up_bal='0';
                }else{
                       $up_bal=$balane_levs;
                }
                // end of  vacations days
                $obj_data->balance = $up_bal;
                $obj_data->description = $request['vacations_description'];
                $obj_data->user_id = $request['evacations_id'];
                $obj_data->start_date = $vacations_start_date;
                $obj_data->resume_date = $vacations_resume_date;
                $obj_data->end_date = $vacations_end_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->save()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Vacations has been added successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'create', 'msg_type' => 'error', 'msg' => 'Vacations Insertion failed!']);
                }
            }
        }
    }

    protected function validator_warnings_info(array $data) {
        return Validator::make($data, [
                    'warnings_on_date' => 'required',
                    'warnings_description' => 'required',
                    'ewarnings_id' => 'required',
                        ], ['ewarnings_id.required' => 'Please select user for warnings!!',]
        );
    }

    public function ewarnings_store(Request $request) {
        $validator_warnings_info = $this->validator_warnings_info($request->all());
        if ($validator_warnings_info->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_warnings_info->getMessageBag()->toArray()]);
        } else {
            $user = Auth::user();
            if (!empty($_POST['warnings_id'])) {
                $warnings_id = $_POST['warnings_id'];
                $warnings_on_date = date('Y-m-d', strtotime($request["warnings_on_date"]));
                $obj_data = UsersWarnings::findorfail($warnings_id);
                $obj_data->description = $request['warnings_description'];
                $obj_data->user_id = $request['ewarnings_id'];
                $obj_data->on_date = $warnings_on_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'update', 'msg_type' => 'success', 'msg' => 'Warnings has been updated successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'update', 'msg_type' => 'error', 'msg' => 'Warnings failed!']);
                }
            } else {
                $warnings_on_date = date('Y-m-d', strtotime($request["warnings_on_date"]));
                $obj_data = new UsersWarnings();
                $obj_data->description = $request['warnings_description'];
                $obj_data->user_id = $request['ewarnings_id'];
                $obj_data->on_date = $warnings_on_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->save()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Warnings has been added successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'create', 'msg_type' => 'error', 'msg' => 'Warnings Insertion failed!']);
                }
            }
        }
    }

    protected function validator_documents_info(array $data) {
        return Validator::make($data, [
                    'document_emp' => 'required',
                    'documents_description' => 'required',
                    'edocuments_id' => 'required',
                        ], ['edocuments_id.required' => 'Please select user for documents!!',]
        );
    }

    public function edocuments_store(Request $request) {
        $validator_documents_info = $this->validator_documents_info($request->all());
        if ($validator_documents_info->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_documents_info->getMessageBag()->toArray()]);
        } else {
            $user = Auth::user();
            if (!empty($_POST['documents_id'])) {
                $documents_id = $_POST['documents_id'];
                $documents_on_date = date('Y-m-d', strtotime($request["documents_on_date"]));
                $obj_data = UsersDocuments::findorfail($documents_id);
                $obj_data->description = $request['documents_description'];
                $obj_data->user_id = $request['edocuments_id'];
                $obj_data->on_date = $documents_on_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'update', 'msg_type' => 'success', 'msg' => 'Warnings has been updated successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'update', 'msg_type' => 'error', 'msg' => 'Warnings failed!']);
                }
            } else {
                $obj_data = new UsersDocuments();
                $file = $request->file('document_emp');
                $documents_description = $request->input("documents_description");
                $edocuments_id = $request->input("edocuments_id");
                $destinationPath = public_path('/uploads');
                $type = $file->getClientOriginalExtension();
                $original_file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $file_name = $original_file_name . "__" . uniqid() . '.' . $type;
                $orig_file_name = $original_file_name . '.' . $type;
                if ($file->move($destinationPath, $file_name)) {
                    $new_uploaded_files['file'] = $file_name;
                    $new_uploaded_files['original_file'] = $orig_file_name;
                    $new_uploaded_files['short_name'] = substr($orig_file_name, 0, 30) . "..";
                    $new_uploaded_files['file_path'] = url('/') . '/public/uploads/' . $file_name;
                    $new_uploaded_files['type'] = $type;
                }
                $obj_data->user_id = $edocuments_id;
                $obj_data->document = $file_name;
                $obj_data->document_description = $documents_description;
                $obj_data->created_by = $user->id;
                if ($obj_data->save()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Documents has been added successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'create', 'msg_type' => 'error', 'msg' => 'Documents Insertion failed!']);
                }
            }
        }
    }

    protected function validator_absences_info(array $data) {
        return Validator::make($data, [
                    'absences_on_date' => 'required',
                    'absences_description' => 'required',
                    'eabsences_id' => 'required',
                        ], ['eabsences_id.required' => 'Please select user for absences!!',]
        );
    }

    public function eabsences_store(Request $request) {
        $validator_absences_info = $this->validator_absences_info($request->all());
        if ($validator_absences_info->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_absences_info->getMessageBag()->toArray()]);
        } else {
            $user = Auth::user();
            if (!empty($_POST['absences_id'])) {
                $absences_id = $_POST['absences_id'];
                $absences_on_date = date('Y-m-d', strtotime($request["absences_on_date"]));
                $obj_data = UsersAbsences::findorfail($absences_id);
                $obj_data->description = $request['absences_description'];
                $obj_data->user_id = $request['eabsences_id'];
                $obj_data->on_date = $absences_on_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'update', 'msg_type' => 'success', 'msg' => 'Absences has been updated successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'update', 'msg_type' => 'error', 'msg' => 'Absences failed!']);
                }
            } else {
                $absences_on_date = date('Y-m-d', strtotime($request["absences_on_date"]));
                $obj_data = new UsersAbsences();
                $obj_data->description = $request['absences_description'];
                $obj_data->user_id = $request['eabsences_id'];
                $obj_data->on_date = $absences_on_date;
                $obj_data->created_by = $user->id;
                if ($obj_data->save()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Absences has been added successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'create', 'msg_type' => 'error', 'msg' => 'Absences Insertion failed!']);
                }
            }
        }
    }

    protected function validator_excuses_info(array $data) {
        return Validator::make($data, [
                    'excuses_on_date' => 'required',
                    'excuses_description' => 'required',
                    'excuses_start_time' => 'required',
                    'excuses_end_time' => 'required',
                    'eexcuses_id' => 'required',
                        ], ['eexcuses_id.required' => 'Please select user for excuses!!',]
        );
    }

    public function eexcuses_store(Request $request) {
        $validator_excuses_info = $this->validator_excuses_info($request->all());
        if ($validator_excuses_info->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_excuses_info->getMessageBag()->toArray()]);
        } else {
            $user = Auth::user();
            if (!empty($_POST['excuses_id'])) {
                $excuses_id = $_POST['excuses_id'];
                $excuses_on_date = date('Y-m-d', strtotime($request["excuses_on_date"]));
                $obj_data = UsersExcuses::findorfail($excuses_id);
                $obj_data->description = $request['excuses_description'];
                $obj_data->user_id = $request['eexcuses_id'];
                $obj_data->on_date = $excuses_on_date;
                $obj_data->start_time = $request['excuses_start_time'];
                $obj_data->end_time = $request['excuses_end_time'];
                $obj_data->created_by = $user->id;
                if ($obj_data->update()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'update', 'msg_type' => 'success', 'msg' => 'Excuses has been updated successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'update', 'msg_type' => 'error', 'msg' => 'Excuses failed!']);
                }
            } else {
                $excuses_on_date = date('Y-m-d', strtotime($request["excuses_on_date"]));
                $obj_data = new UsersExcuses();
                $obj_data->description = $request['excuses_description'];
                $obj_data->user_id = $request['eexcuses_id'];
                $obj_data->on_date = $excuses_on_date;
                $obj_data->start_time = $request['excuses_start_time'];
                $obj_data->end_time = $request['excuses_end_time'];
                $obj_data->created_by = $user->id;
                if ($obj_data->save()) {
                    return response()->json(['success' => TRUE, 'data' => $obj_data, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Excuses has been added successfully!']);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'create', 'msg_type' => 'error', 'msg' => 'Excuses Insertion failed!']);
                }
            }
        }
    }

    protected function validator_attendances_info(array $data) {
        return Validator::make($data, [
                    'attendances_type' => 'required',
                        ], ['attendances_type.required' => 'Something went wrong!!',]
        );
    }

    public function eattendances_store(Request $request) {
        $validator_attendances_info = $this->validator_attendances_info($request->all());
        if ($validator_attendances_info->fails()) {
            return response()->json(['success' => FALSE, 'errors' => $validator_attendances_info->getMessageBag()->toArray()]);
        } else {
            $on_date = date("Y-m-d", time());
            $ctime = date("H:i:s", time());
            $user = Auth::user();
            $user_id = $user->id;
            $type = $request['attendances_type'];
            $obj_data = UsersAttendances::where("on_date", "=", $on_date)->where("user_id", "=", $user_id)->first();
            $status = false;
            if (!empty($obj_data)) {
                if ($type == "IN") {
                    $obj_data->last_type = $type;
                    $msg = 'Sign In completed successfully!';
                } else if ($type == "OUT") {
                    $obj_data->out_time = $ctime;
                    $obj_data->last_type = $type;
                    $msg = 'Sign Out completed successfully!';
                }
                
                if ($obj_data->update()) {
                    $status = $this->addAttendancesEntries($obj_data->id, $type, $ctime);
                    $status = true;
                }
                if ($status == true) {
                    return response()->json(['success' => TRUE, 'op' => 'update', 'msg_type' => 'success', 'msg' => $msg]);
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'update', 'msg_type' => 'error', 'msg' => 'Something went wrong!']);
                }
            } else {
                $obj_data = new UsersAttendances();
                $obj_data->on_date = $on_date;
                $obj_data->in_time = $ctime;
                $obj_data->user_id = $user_id;
                $obj_data->last_type = $type;
                if ($type == "IN") {
                    if ($obj_data->save()) {
                        $this->addAttendancesEntries($obj_data->id, $type, $ctime);
                        return response()->json(['success' => TRUE, 'op' => 'create', 'msg_type' => 'success', 'msg' => 'Sign In completed successfully!']);
                    } else {
                        return response()->json(['success' => FALSE, 'op' => 'create', 'msg_type' => 'error', 'msg' => 'Something went wrong!']);
                    }
                } else {
                    return response()->json(['success' => FALSE, 'op' => 'update', 'msg_type' => 'error', 'msg' => 'Something went wrong!']);
                }
            }
        }
    }

    public function addAttendancesEntries($attendances_id, $type, $on_time) {
        $obj_data = new AttendancesEntries();
        $obj_data->attendances_id = $attendances_id;
        $obj_data->type = $type;
        $obj_data->on_time = $on_time;
        if ($obj_data->save()) {
            return true;
        } else {
            return false;
        }
    }

    public function eattendances_list(Request $request) {
        $year = $request['attendances_year'];
        $month = $request['attendances_month'];
        if (empty($year)) {
            $year = date("Y", time());
        }
        if (empty($month)) {
            $month = date("m", time());
        }
        $user = Auth::user();
        $user_id = $user->id;
        $obj_data = UsersAttendances::where("YEAR(on_date)", "=", $year)->where("MONTH(on_date)", "=", $month)->where("user_id", "=", $user_id)->all();
        if (!empty($obj_data)) {
            return response()->json(['success' => TRUE, 'op' => 'list', 'msg_type' => 'success', 'data' => $obj_data]);
        } else {
            
        }
    }
    public function get_cab_details(Request $request){
         $cab_id = $request['cab_id']; $vehicle='';$user_details='';
        if($cab_id){
            $user_details = JobCard::where("cab_no", $cab_id)->get();
            if($user_details){
                foreach ($user_details as $key1 => $value) {
                    $vehicle=$value['type'];
                if($vehicle){
                      $user_details[$key1]['make'] = $vehicle;
                                return response()->json(['success' => TRUE, 'user_details' => $user_details]);
                   }
                    
                }
            }
                 
        }   
    }

    public function check_acc_code(){
        $acc_cde=$_POST['acc_code']; $acc_id='0';
        if($acc_cde){
           $acc_code=User::where('acc_code','=',$acc_cde)->get(); 
           if($acc_code){
            foreach ($acc_code as $key => $value) {
                     $acc_id=$value['id'];
                     
            }
            if($acc_id!='0'){
                return response()->json(['success' => true, 'data' => $acc_id]); 
            }else{
                    return response()->json(['success' => false, 'data' => '0']); 
            }
            
           }
        }
    }


}
