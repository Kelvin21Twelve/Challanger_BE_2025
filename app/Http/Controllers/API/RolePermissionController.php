<?php

namespace App\Http\Controllers\API;

use App\Permission;
use App\RolesPermission;
use App\Role;
use App\User;
use App\JobTitle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolePermissionController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function store(Request $request) {
        $permissions = Permission::all();
       // print_r($permissions); die();
        $permission_array = array();
        foreach ($permissions as $value) {
            $permission_array[$value->slug] = $value->id;
        }
        $role_id = $request->input("role_id");
        RolesPermission::where('role_id', '=', $role_id)->delete();
        $permission_slug = array();
        $settings_mnu = array(
            "make-view" => "make-view",
            "model-view" => "model-view",
            "color-view" => "color-view",
            "nationality-view" => "nationality-view",
            "visa-type-view" => "visa-type-view",
            "vacation-type-view" => "vacation-type-view",
            "labour-view" => "labour-view",
            "supplier-view" => "supplier-view",
            "agency-view" => "agency-view",
            "job-title-view" => "job-title-view",
            "system-user-view" => "system-user-view",
            "department-view" => "department-view",
            "visa-notification-view" => "visa-notification-view",
            "announcement-view" => "announcement-view",
            "piechart-view" => "piechart-view",
            "pie-diagram-view" => "pie-diagram-view",
            "dashboard-total-view" =>"dashboard-total-view",
            "dash-view" =>"dash-view",

        );
        $customers_mnu = array("customer-view" => "customer-view");
        $inventory_mnu = array(
            "used-spare-parts-view" => "used-spare-parts-view",
            "new-spare-parts-view" => "new-spare-parts-view"
        );
        $cars_mnu = array("car-view" => "car-view");
        $jobs_mnu = array("job-card-view" => "job-card-view");

        $i_p = ($request->input("permission")) ? $request->input("permission") : array();
        //$i_p = explode(',', $i_p);  
        if (in_array("make-add", $i_p) || in_array("make-edit", $i_p) || in_array("make-delete", $i_p)) {

            $i_p['make-view'] = "make-view";
        }
        if (in_array("model-add", $i_p) || in_array("model-edit", $i_p) || in_array("model-delete", $i_p)) {

            $i_p['model-view'] = "model-view";
        }
        if (in_array("holidays-add", $i_p) || in_array("holidays-edit", $i_p) || in_array("holidays-delete", $i_p)) {
            $i_p['holidays-view'] = "holidays-view";
        }
        if (in_array("color-add", $i_p) || in_array("color-edit", $i_p) || in_array("color-delete", $i_p)) {
            $i_p['color-view'] = "color-view";
        }
        if (in_array("nationality-add", $i_p) || in_array("nationality-edit", $i_p) || in_array("nationality-delete", $i_p)) {
            $i_p['nationality-view'] = "nationality-view";
        }
        if (in_array("visa-type-add", $i_p) || in_array("visa-type-edit", $i_p) || in_array("visa-type-delete", $i_p)) {
            $i_p['visa-type-view'] = "visa-type-view";
        }
        if (in_array("vacation-type-add", $i_p) || in_array("vacation-type-edit", $i_p) || in_array("vacation-type-delete", $i_p)) {
            $i_p['vacation-type-view'] = "vacation-type-view";
        }
        if (in_array("labour-add", $i_p) || in_array("labour-edit", $i_p) || in_array("labour-delete", $i_p)) {
            $i_p['labour-view'] = "labour-view";
        }
        if (in_array("supplier-add", $i_p) || in_array("supplier-edit", $i_p) || in_array("supplier-delete", $i_p)) {
            $i_p['supplier-view'] = "supplier-view";
        }
        if (in_array("agency-add", $i_p) || in_array("agency-edit", $i_p) || in_array("agency-delete", $i_p)) {
            $i_p['agency-view'] = "agency-view";
        }
        if (in_array("job-title-add", $i_p) || in_array("job-title-edit", $i_p) || in_array("job-title-delete", $i_p)) {
            $i_p['job-title-view'] = "job-title-view";
        }
        if (in_array("system-user-add", $i_p) || in_array("system-user-edit", $i_p) || in_array("system-user-delete", $i_p)) {
            $i_p['system-user-view'] = "system-user-view";
        }
        if (in_array("department-add", $i_p) || in_array("department-edit", $i_p) || in_array("department-delete", $i_p)) {
            $i_p['department-view'] = "department-view";
        }
        if (in_array("customer-add", $i_p) || in_array("customer-edit", $i_p) || in_array("customer-delete", $i_p)) {
            $i_p['customer-view'] = "customer-view";
        }
        if (in_array("used-spare-parts-add", $i_p) || in_array("used-spare-parts-edit", $i_p) || in_array("used-spare-parts-delete", $i_p)) {
            $i_p['used-spare-parts-view'] = "used-spare-parts-view";
        }
        if (in_array("new-spare-parts-add", $i_p) || in_array("new-spare-parts-edit", $i_p) || in_array("new-spare-parts-delete", $i_p)) {
            $i_p['new-spare-parts-view'] = "new-spare-parts-view";
        }
        if (in_array("car-add", $i_p) || in_array("car-edit", $i_p) || in_array("car-delete", $i_p)) {
            $i_p['car-view'] = "car-view";
        }
        if (in_array("spare-parts-sales-form-add", $i_p) || in_array("spare-parts-sales-form-edit", $i_p) || in_array("spare-parts-sales-form-delete", $i_p)) {
            $i_p['spare-parts-sales-form-view'] = "spare-parts-sales-form-view";
        }
        if (in_array("job-card-add", $i_p) || in_array("job-card-edit", $i_p) || in_array("job-card-delete", $i_p)) {
            $i_p['job-card-view'] = "job-card-view";
        }
        if (in_array("customers-used-spare-parts-add", $i_p) || in_array("customers-used-spare-parts-edit", $i_p) || in_array("customers-used-spare-parts-delete", $i_p)) {
            $i_p['customers-used-spare-parts-view'] = "customers-used-spare-parts-view";
        }
        if (in_array("customers-new-spare-parts-add", $i_p) || in_array("customers-new-spare-parts-edit", $i_p) || in_array("customers-new-spare-parts-delete", $i_p)) {
            $i_p['customers-new-spare-parts-view'] = "customers-new-spare-parts-view";
        }
        if (in_array("customers-labour-add", $i_p) || in_array("customers-labour-edit", $i_p) || in_array("customers-labour-delete", $i_p)) {
            $i_p['customers-labour-view'] = "customers-labour-view";
        }
        if (in_array("memo-add", $i_p) || in_array("memo-edit", $i_p) || in_array("memo-delete", $i_p)) {
            $i_p['memo-view'] = "memo-view";
        }
        if (in_array("visa-notification-add", $i_p) || in_array("visa-notification-edit", $i_p) || in_array("visa-notification-delete", $i_p)) {
            $i_p['visa-notification-view'] = "visa-notification-view";
        }

        if (in_array("announcement-add", $i_p) || in_array("announcement-edit", $i_p) || in_array("announcement-delete", $i_p)) {
            $i_p['announcement-view'] = "announcement-view";
        }

        if (in_array("piechart-add", $i_p) || in_array("piechart-edit", $i_p) || in_array("piechart-delete", $i_p)) {
            $i_p['piechart-view'] = "piechart-view";
        }

        if (in_array("pie-diagram-add", $i_p) || in_array("pie-diagram-edit", $i_p) || in_array("pie-diagram-delete", $i_p)) {
            $i_p['pie-diagram-view'] = "pie-diagram-view";
        }

        if (in_array("dashboard-total-add", $i_p) || in_array("dashboard-total-edit", $i_p) || in_array("dashboard-total-delete", $i_p)) {
            $i_p['dashboard-total-view'] = "dashboard-total-view";
        }

        if (in_array("dash-view-add", $i_p) || in_array("dash-view-edit", $i_p) || in_array("dash-view-delete", $i_p)) {
            $i_p['dash-view'] = "dash-view";
        }

        // echo '<pre>';
       //print_r($i_p);exit;

       if ($i_p) {

            foreach ($i_p as $slug) {

                $permission_slug[] = $slug;
               // print_r($slug);exit;
                (in_array($slug, $settings_mnu) && !in_array("setting-menu-visible", $permission_slug)) ? $permission_slug[] = "setting-menu-visible" : "";
                (in_array($slug, $customers_mnu) && !in_array("customer-menu-visible", $permission_slug)) ? $permission_slug[] = "customer-menu-visible" : "";
                (in_array($slug, $inventory_mnu) && !in_array("inventory-menu-visible", $permission_slug)) ? $permission_slug[] = "inventory-menu-visible" : "";
                (in_array($slug, $cars_mnu) && !in_array("car-menu-visible", $permission_slug)) ? $permission_slug[] = "car-menu-visible" : "";
                (in_array($slug, $jobs_mnu) && !in_array("job-menu-visible", $permission_slug)) ? $permission_slug[] = "job-menu-visible" : "";
                $role_permission = new RolesPermission;
                $role_permission->role_id = $role_id;
              //  print_r($permission_array[$slug]);exit;
                $role_permission->permission_id = $permission_array[$slug];
                $role_permission->save();
            }
        }
        $json_permission = json_encode($permission_slug);
        //$json_permission = implode(",",$i_p);exit;
        Role::find($role_id)->update(['permission_slug' => $json_permission]);
       // $role_permission = new RolesPermission;
        $all_role_permission = RolesPermission::all();
        return response()->json(['success' => true, 'data' => $all_role_permission, "slug" => $json_permission]);
    }

    public function update(Request $request, $id) {
        $permission = Permission::find($id);
        if ($permission) {
            $permission->permission = json_encode($request->input("permission"));
            $permission->save();
            return response()->json(['success' => true, 'data' => $permission]);
        } else {
            return response()->json(['success' => false, 'data' => ""]);
        }
    }

    public function chk_permission_user(Request $request){
      $details=''; $role='';
       $user_id=$request->input("user_id");
       if($user_id){
          $user_details=User::find($user_id);
          if($user_details){
              $dept_details=JobTitle::find($user_details->department);
               if($dept_details){
                    //print_r($dept_details); die();
                    if($dept_details->job_title=='Administrator'){
                      $role='Admins';
                    }
                    $per_details=Role::where('name','=',$role)->get();
                    if($per_details){
                        foreach ($per_details as $key => $value) {
                          $permission_slug = json_decode($value['permission_slug']);
                          if(in_array("dash-view", $permission_slug)) {
                             return response()->json(['success' => true, 'data' => '1']);
                          }else{
                                  return response()->json(['success' => false, 'data' => "0"]);
                          }

                        }

                    }
              }
          }
       }
    }

    public function store_role(request $request){
      $role = new Role();
      $role->fill($request->all());
      $role->description = lcfirst($request->description);
      $role->slug = lcfirst($request->name);
      $role->save();
      return response()->json(['success' => true, 'data' => $role]);
    }

    public function delete_role(request $request){
     //echo "<pre>"; print_r($request->all()); die();
     $user=User::where('department','=',$request['role_id'])->count();
     if($user > 0){  
        return response()->json(['success' => false,'data'=>'']);
     }else{
        $data=Role::where('id','=',$request['role_id'])->update(['is_delete'=>'1']);
        if($data){
            return response()->json(['success' => true,'data'=>$data]);
        }
      }
    } 

}
