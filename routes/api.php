    <?php

use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
 */
Route::post('login', 'API\UserController@login');
Route::get('get_logo', 'API\UserController@get_logo');


Route::get('collection_table', 'API\CommonController@collection_table');

Route::get('/sendtomail', 'SendEmailController@sendToMail');

Route::post('/coontactus_store', 'ContactUsController@store')->name('coontactus_store'); 

Route::get('/visa_mail', 'API\CronController@renew_visa');

Route::get('/vacation_mail', 'API\CronController@renew_vacation');
    Route::get('get_nationality', 'API\NationalityController@get_nationality');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:api'], function() {
   
    Route::put('common_delete/{id}', 'API\CommonController@delete');
    Route::post('syncDB', 'API\CommonController@syncDB');
    Route::get('cabNo', 'API\CommonController@cabNo');
    Route::get('getCabs', 'API\CommonController@get_cabs');
    Route::post('search_customer', 'API\CommonController@search_customer');
    Route::post('transfer_cab', 'API\CommonController@transfer_cab');
    Route::post('lock_unlock_jobcard', 'API\CommonController@lock_unlock_jobcard');

    //---------------- Make --------------------------
    Route::post('make_insert', 'API\MakeController@store')->middleware(['middleware' => 'permission:make-add']);
    Route::put('make_update/{id}', 'API\MakeController@update')->middleware(['middleware' => 'permission:make-edit']);
    
    //---------------- Model --------------------------
    Route::post('model_insert', 'API\ModelController@store')->middleware(['middleware' => 'permission:model-add']);
    Route::put('model_update/{id}', 'API\ModelController@update')->middleware(['middleware' => 'permission:model-edit']);
    
    // Route::post('get_make_name', 'API\ModelController@get_make_name');
    // Route::get('model_get_data', 'API\ModelController@get');
    Route::post('get_model', 'API\ModelController@get_model')->name('get_model');
    Route::post('get_car_model_year', 'API\ModelController@get_car_model_year')->name('get_car_model_year');
    
    // Route::post('model_del', 'API\ModelController@model_del');

    //---------------- Color --------------------------
    Route::post('color_insert', 'API\ColorController@store')->middleware(['middleware' => 'permission:color-add']);
    Route::put('color_update/{id}', 'API\ColorController@update')->middleware(['middleware' => 'permission:color-edit']);

    //---------------- Nationality --------------------------
    Route::post('nationality_insert', 'API\NationalityController@store')->middleware(['middleware' => 'permission:nationality-add']);
    Route::put('nationality_update/{id}', 'API\NationalityController@update')->middleware(['middleware' => 'permission:nationality-edit']);

    //---------------- Visa Type --------------------------
    Route::post('visa_type_insert', 'API\VisaTypeController@store')->middleware(['middleware' => 'permission:visa-type-add']);
    Route::put('visa_type_update/{id}', 'API\VisaTypeController@update')->middleware(['middleware' => 'permission:visa-type-edit']);
    Route::post('update_end_visa', 'API\VisaTypeController@update_visa_end_date');

    //---------------- Vacation Type --------------------------
    Route::post('vac_type_insert', 'API\VacTypeController@store')->middleware(['middleware' => 'permission:vacation-type-add']);
    Route::put('vac_type_update/{id}', 'API\VacTypeController@update')->middleware(['middleware' => 'permission:vacation-type-edit']);
    Route::post('get_vacation_bal','API\VacTypeController@get_user_vac_bal');
    Route::post('check_vaction_renew', 'API\VacTypeController@get_user_vac_renew_date');
    Route::post('update_end_vac', 'API\VacTypeController@update_user_vac_renew_date');

    //---------------- service type --------------------------
    
    Route::post('labourservicetype_insert', 'API\ServiceTypeController@store');
    Route::put('labourservicetype_update/{id}', 'API\ServiceTypeController@update');
    Route::get('get_labourservicetype', 'API\ServiceTypeController@get_labourservicetype');


    //---------------- Supplier ---------------------------
    Route::post('supplier_insert', 'API\SupplierController@store')->middleware(['middleware' => 'permission:supplier-add']);
    Route::put('supplier_update/{id}', 'API\SupplierController@update')->middleware(['middleware' => 'permission:supplier-edit']);
    Route::get('get_supplier', 'API\SupplierController@get_supplier');
    
    Route::post('add_supplier_payment_acc', 'API\SupplierController@add_payment_acc');
    Route::post('get_supplier_payment_acc_deatils', 'API\SupplierController@get_supplier_payment_acc_deatils');

    //----------------- Agency ---------------------------
    Route::post('agency_insert', 'API\AgencyController@store')->middleware(['middleware' => 'permission:agency-add']);
    Route::put('agency_update/{id}', 'API\AgencyController@update')->middleware(['middleware' => 'permission:agency-edit']);

    //----------------- Vehicle ---------------------------
    Route::post('car_insert', 'API\VehicleController@store')->middleware(['middleware' => 'permission:car-add']);
    Route::put('car_update/{id}', 'API\VehicleController@update')->middleware(['middleware' => 'permission:car-edit']);
    Route::post('get_car_details', 'API\VehicleController@index');
    Route::get('get_job_cards_vehicle', 'API\VehicleController@get_job_cards_vehicle');

    //---------------- Customer --------------------------
    Route::post('customer_insert', 'API\CustomerController@store')->middleware(['middleware' => 'permission:customer-add']);
    Route::put('customer_update/{id}', 'API\CustomerController@update')->middleware(['middleware' => 'permission:customer-edit']);
    
    //----------------- Engine ---------------------------
    Route::post('engine_insert_rec', 'API\EngineController@store');
    Route::put('engine_update/{id}', 'API\EngineController@update'); 

    //----------------- Brand ------------------
    Route::post('brand_insert', 'API\BrandController@store');
    Route::put('brand_update/{id}', 'API\BrandController@update');

    //----------------- System Users------------------
    Route::post('details', 'API\UserController@details');
    Route::post('change_password', 'API\UserController@change_password');
    Route::post('register', 'API\UserController@register');
    Route::put('user_update/{id}', 'API\UserController@update');
    Route::post('check_visa_expiry', 'API\UserController@check_visa_expiry_details');

    //----------------- invoice type ------------------
    Route::post('invoicetype_insert', 'API\InvcTypeController@store');
    Route::put('invoicetype_update/{id}', 'API\InvcTypeController@update');

    //----------------- Department ------------------
    Route::post('add_roles', 'API\RolePermissionController@store_role');
    Route::post('permission_insert', 'API\RolePermissionController@store');
    Route::put('permission_update/{id}', 'API\RolePermissionController@update');
    Route::post('chk_permission_user', 'API\RolePermissionController@chk_permission_user');
    Route::post('delete_role', 'API\RolePermissionController@delete_role');

    //---------------- memos --------------------------
    Route::post('memo_insert', 'API\MemoController@store');
    Route::put('memo_update/{id}', 'API\MemoController@update');

    //----------------- Used Spare Parts ------------------
    Route::post('used_parts_insert', 'API\UsedSparePartsController@store')->middleware(['middleware' => 'permission:used-spare-parts-add']);
    Route::put('used_parts_update/{id}', 'API\UsedSparePartsController@update')->middleware(['middleware' => 'permission:used-spare-parts-edit']);
    Route::post('update_count', 'API\UsedSparePartsController@update_bal');
    Route::post('get_user_parts_details', 'API\UsedSparePartsController@index');
    Route::get('get_used_spare_parts', 'API\UsedSparePartsController@get_used_spare_parts_data');
    Route::post('update_delete_bal', 'API\UsedSparePartsController@update_delete_bal');
    
    //----------------- New Spare Parts ------------------
    Route::post('new_parts_insert', 'API\NewSparePartsController@store')->middleware(['middleware' => 'permission:new-spare-parts-add']);
    Route::put('new_parts_update/{id}', 'API\NewSparePartsController@update')->middleware(['middleware' => 'permission:new-spare-parts-edit']);
    Route::post('get_minlimit_spare', 'API\NewSparePartsController@check_min_limit');
    
    Route::post('newspareexcel_add', 'API\NewSparePartsController@newspareexcel_add');
    Route::post('get_supplier_new_spare_details', 'API\NewSparePartsController@get_supplier_new_spare_details');
    // Route::get('update_count_new_spare_prt', 'API\NewSparePartsController@update_count_bal');
    
    //-------------------Dashboard-------------------------------------  
    Route::get('get_spare_parts_count', 'API\DashboardController@get_spare_parts_count_all');
    Route::get('get_jobs_count', 'API\DashboardController@get_jobs_count_all');
    Route::get('get_newspare_count', 'API\DashboardController@get_newspare_count_count_all');
    Route::get('get_usedspare_count', 'API\DashboardController@get_usedspare_count_count_all');
    Route::get('get_customer', 'API\DashboardController@get_cust_count_all');
    Route::get('get_customeracc', 'API\DashboardController@get_customeracc');

    //----------------- Job Title ---------------------------
    Route::post('job_title_insert', 'API\JobTitleController@store')->middleware(['middleware' => 'permission:job-title-add']);
    Route::put('job_title_update/{id}', 'API\JobTitleController@update')->middleware(['middleware' => 'permission:job-title-edit']);

    //----------------- Job Card ---------------------------
    Route::post('job_card_insert', 'API\JobCardController@store')->middleware(['middleware' => 'permission:job-card-add']);
    Route::put('job_card_update/{id}', 'API\JobCardController@update')->middleware(['middleware' => 'permission:job-card-edit']);
    Route::put('job_card_delete/{id}', 'API\JobCardController@delete');
    Route::get('get_job_cards', 'API\JobCardController@get_job_cards');
    Route::get('get_Empty_job_cards', 'API\JobCardController@get_Empty_job_cards');
    
    Route::get('get_complete_jobs', 'API\JobCardController@get_complete_jobs');
    Route::post('get_complete_job_details', 'API\JobCardController@get_complete_job_details');
    
    Route::get('get_cab_history', 'API\JobCardController@get_cab_history');
    
    
    Route::post('search_master', 'API\JobCardController@search_master');
    Route::post('get_payment_details', 'API\JobCardController@get_payment_details');
    
    Route::post('get_name_of_job_created', 'API\JobCardController@created_by');
    Route::get('get_item_code', 'API\JobCardController@get_item_code');
    Route::post('get_item_code_details', 'API\JobCardController@get_item_code_details');
    Route::post('get_engine_types', 'API\JobCardController@get_engine_types');
    
    
    // get_item_code

    //----------------- HRMS--------------------------------
    Route::post('employee_insert', 'API\UsersController@user_store');
    Route::post('employee_search', 'API\UsersController@index');
    Route::post('eadditions_store', 'API\UsersController@eadditions_store');
    Route::post('edeductions_store', 'API\UsersController@edeductions_store');
    Route::post('evacations_store', 'API\UsersController@evacations_store');
    Route::post('ewarnings_store', 'API\UsersController@ewarnings_store');
    Route::post('edocuments_store', 'API\UsersController@edocuments_store');
    Route::post('eabsences_store', 'API\UsersController@eabsences_store');
    Route::post('eexcuses_store', 'API\UsersController@eexcuses_store');
    Route::post('eattendances_store', 'API\UsersController@eattendances_store');
    Route::post('get_cab_details', 'API\UsersController@get_cab_details');
    Route::post('check_acc_code', 'API\UsersController@check_acc_code');

    //----------------- Customers Used Spare Parts -----------
    Route::post('cust_used_spare_parts_insert', 'API\CustUsedSparePartsController@store')->middleware(['middleware' => 'permission:customers-used-spare-parts-add']);
    Route::put('cust_used_spare_parts_update/{id}', 'API\CustUsedSparePartsController@update')->middleware(['middleware' => 'permission:customers-used-spare-parts-edit']);
    // Route::get('update_used_spare_part_balance', 'API\CustUsedSparePartsController@update_used_spare_part_balance');

    //----------------- Labours ------------------
    Route::post('labours_insert', 'API\LabourController@store')->middleware(['middleware' => 'permission:labour-add']);
    Route::put('labours_update/{id}', 'API\LabourController@update')->middleware(['middleware' => 'permission:labour-edit']);
    Route::post('get_labour', 'API\LabourController@get_labour');
    

    //----------------- Customers New Spare Parts ------------------
    Route::post('cuts_new_spare_parts_insert', 'API\CustNewSparePartsController@store')->middleware(['middleware' => 'permission:customers-new-spare-parts-add']);
    Route::post('get_new_spare_parts_useds', 'API\CustNewSparePartsController@get_new_spare_parts_useds');
    
    //----------------- Customers Labour ------------------
    Route::post('cuts_labours_insert', 'API\CustsLaboursController@store')->middleware(['middleware' => 'permission:customers-labour-add']);
    Route::put('custs_labours_update/{id}', 'API\CustsLaboursController@update')->middleware(['middleware' => 'permission:customers-labour-edit']);
    Route::post('get_cust_labour_list', 'API\CustsLaboursController@get_cust_labour_list');
    Route::post('add_customers_labours', 'API\CustsLaboursController@add_customers_labours');
    
    //----------------- Announcement ------------------
    Route::post('ann_insert', 'API\AnnouncementController@store');
    Route::put('ann_update/{id}', 'API\AnnouncementController@update');

    //----------------- Holyday -----------------------
    Route::post('holyday_insert', 'API\HolydayController@store');
    Route::put('holyday_update/{id}', 'API\HolydayController@update');
    Route::post('holyday_search', 'API\HolydayController@holyday_search');

    //----------------- Job Card Payment  --------------------------------------
    Route::post('payment_insert', 'API\JobCardPaymentController@store');
    Route::post('insert_labour_discount_entry', 'API\JobCardPaymentController@insert_labour_discount_entry');

    //----------------- Account -----------------------
    Route::post('account_insert', 'API\AccountController@store');
    Route::put('account_update/{id}', 'API\AccountController@update');
    Route::post('account_details_print', 'API\AccountController@search_account');
    Route::post('check_account_code', 'API\AccountController@check_account_exist');

    //----------------- Account -----------------------Dev By Rohit
    Route::post('expense_insert', 'API\ExpenseController@store');
    Route::put('expense_update/{id}', 'API\ExpenseController@update');
    Route::post('expense_details_print', 'API\ExpenseController@search_expense');
    Route::post('check_expense_code', 'API\ExpenseController@check_expense_exist');

    Route::post('expense_type_insert', 'API\ExpenseController@store');
    Route::put('expense_type_update/{id}', 'API\ExpenseController@update');

    //----------------- SparePartReturn ----------------------- 
    Route::post('spare_part_soled', 'API\SparePartReturnController@search_spare_part_soled');
    Route::post('spare_part_returned', 'API\SparePartReturnController@search_spare_part_returned');
    Route::post('spare_part_to_return', 'API\SparePartReturnController@return_spare_part_store');
    Route::post('spare_part_to_return_table', 'API\SparePartReturnController@return_spare_part_to_add');
    Route::get('sparegetCabs', 'API\SparePartReturnController@spare_get_cabs');

    //----------------- GeneralLedger ----------------------- 
    Route::post('general_ledger_insert', 'API\GeneralLedgerController@gnrl_ledgrinsert');
    Route::post('general_ledger_search', 'API\GeneralLedgerController@gnrl_ledgrsrch');
    Route::post('general_ledger_get', 'API\GeneralLedgerController@gnrl_ledget');
    Route::post('general_ledger_edit', 'API\GeneralLedgerController@gnrl_ledgredit');
    Route::post('general_ledger_delete', 'API\GeneralLedgerController@gnrl_ledgrdel');
    // Route::get('geta_account_credit_debit', 'API\GeneralLedgerController@get_account_credit_debit');

    //----------------- SparePartsPurchase -----------------------
    Route::post('add_spare_purcahse', 'API\SparePartsPurchaseController@add_spare_purcahse_data');
    Route::post('spare_part_purchased', 'API\SparePartsPurchaseController@spare_part_purchased_data');
    Route::post('get_view_purchase_details', 'API\SparePartsPurchaseController@get_view_purchase_details');
    Route::post('spare_part_history', 'API\SparePartsPurchaseController@spare_part_history_data');
    Route::post('order_history', 'API\SparePartsPurchaseController@order_history');
    Route::post('purchase_return', 'API\SparePartsPurchaseController@purchase_return'); 
    Route::post('get_purchase_history', 'API\SparePartsPurchaseController@get_purchase_history');
    Route::post('get_invoice_number', 'API\SparePartsPurchaseController@get_invoice_number');
    

    //----------------- PostInvoice-----------------------
    Route::post('search_pst_invoice', 'API\PostInvoiceController@post_inv_srch'); 
    
    //----------------- Payroll-----------------------
    Route::post('search_payroll', 'API\PayrollController@search_payroll_data');
    Route::post('get_payroll', 'API\PayrollController@get_payroll_data');
    
    //----------------- SalaryRel-----------------------
    Route::post('get_salaryrel','API\SalaryRelController@get_user_sal');

    //----------------- Common------------------
    Route::post('commons_delete', 'Common\CommonsController@delete');
    Route::post('commons_listing', 'Common\CommonsController@listing');
    Route::post('commons_iupdate', 'Common\CommonsController@iupdate');
    
    //=======================Hruturaj==============================================

    //------------------ Job Card Report ------------------------------------
    Route::post('print_all_job_card', 'API\ReportController@print_all_job_card');
    Route::post('print_posted_card', 'API\ReportController@print_posted_card');
    Route::post('print_unposted_card', 'API\ReportController@print_unposted_card');
    Route::post('print_canceled_card', 'API\ReportController@print_canceled_card');
    Route::post('print_labours_card', 'API\ReportController@print_labours_card');

    //------------------ inventory and Payments-------------------------------
    Route::post('print_users_target_report', 'API\ReportController@print_users_target_report');
    Route::post('print_end_of_day', 'API\ReportController@print_end_of_day');
    Route::post('print_spare_parts_purchase', 'API\ReportController@print_spare_parts_purchase');
    Route::post('print_spare_parts_net_profit', 'API\ReportController@print_spare_parts_net_profit');
    Route::post('print_inventory', 'API\ReportController@print_inventory');

    //-------------------------CLEAR ALL INVENTORY RECORDS --------------- DEV by rohit
    Route::post('clear_inventory', 'API\UserController@clear_inventory');
    Route::post('clear_all_tables', 'API\UserController@clear_all_tables');
    Route::post('insert_notification', 'API\SparePartsPurchaseController@insert_notification');
    Route::get('get_jobCard_notifications', 'API\JobCardController@get_jobCard_notifications');


    Route::post('print_supplier_report', 'API\ReportController@print_supplier_report');
    Route::post('mail_supplier_report', 'API\ReportMailController@mail_supplier_report');
    
    Route::post('print_expense_report', 'API\ReportController@print_expense_report');
    Route::post('mail_expense_report', 'API\ReportMailController@mail_expense_report');

    //END//

    //------------------ Spare part sales-------------------------------------
    Route::post('print_all_spare_parts_sale', 'API\ReportController@print_all_spare_parts_sale');
    Route::post('print_with_job_card', 'API\ReportController@print_with_job_card');
    Route::post('print_without_job_card', 'API\ReportController@print_without_job_card');

    //-------------------Payments reports-------------------------------------
    Route::post('print_Payments_report', 'API\ReportController@print_Payments_report');
    Route::post('print_daily_details', 'API\ReportController@print_daily_details');
    Route::post('print_daily_summery', 'API\ReportController@print_daily_summery');

    //-------------------Spare part purchase reports--------------------------
    Route::post('print_all_sp_part_purchase', 'API\ReportController@print_all_sp_part_purchase');
    Route::post('print_post_sp_part_purchase', 'API\ReportController@print_post_sp_part_purchase');
    Route::post('print_unpost_sp_part_purchase', 'API\ReportController@print_unpost_sp_part_purchase');
    Route::post('print_job_card', 'API\ReportController@print_job_card');
    Route::post('print_customer_payment', 'API\ReportController@print_customer_payment');
    Route::post('print_customer_details', 'API\ReportController@print_customer_details');
    Route::post('print_account_details','API\ReportController@print_account_details_All');
    Route::post('print_salary_slip', 'API\ReportController@print_salary_slip');
    Route::post('import_attcsv', 'API\ReportController@import_attcsv');
    Route::post('print_payroll_details','API\ReportController@print_payroll_data');
    Route::post('print_sal_rel_details','API\ReportController@get_user_sal_detail');

    Route::post('print_invoice_details', 'API\ReportController@print_invoice_details');
    Route::post('print_complete_jobcard', 'API\ReportController@print_complete_jobcard');
    // print_complete_jobcard

    //------------------ ReportMail-------------------------------
    Route::post('mail_daily_details', 'API\ReportMailController@mail_daily_details');
    Route::post('mail_daily_summery', 'API\ReportMailController@mail_daily_summery');
    Route::post('mail_posted_card', 'API\ReportMailController@mail_posted_job_card');
    Route::post('mail_unposted_card', 'API\ReportMailController@mail_unposted_job_card');
    Route::post('mail_canceled_card', 'API\ReportMailController@mail_canceled_job_card');
    Route::post('mail_labours_card', 'API\ReportMailController@mail_labours_job_card');
    Route::post('mail_all_sp_part_purchase', 'API\ReportMailController@mail_all_sp_part_purchase');
    Route::post('mail_post_sp_part_purchase', 'API\ReportMailController@mail_post_sp_part_purchase');
    Route::post('mail_unpost_sp_part_purchase', 'API\ReportMailController@mail_unpost_sp_part_purchase');
    Route::post('mail_users_target_report', 'API\ReportMailController@mail_users_target_report');
    Route::post('mail_end_of_day', 'API\ReportMailController@mail_end_of_day');
    Route::post('mail_spare_parts_net_profit', 'API\ReportMailController@mail_spare_parts_net_profit');
    Route::post('mail_inventory', 'API\ReportMailController@mail_inventory');
    Route::post('mail_customer_details', 'API\ReportMailController@mail_customer_details');
    Route::post('mail_all_job_card_details', 'API\ReportMailController@mail_all_job_cards');
    Route::post('mail_all_spare_parts_sale', 'API\ReportMailController@all_spare_parts_sales');
    Route::post('mail_with_job_card', 'API\ReportMailController@posted_spare_parts_sales');
    Route::post('mail_without_job_card', 'API\ReportMailController@mail_without_job_card');

    //------------------ ReportMail-------------------------------
    // Route::get('googlemap/direction', 'MapController@direction');
    
    
    



    
 
    

});
