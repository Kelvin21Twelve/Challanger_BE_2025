<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SendEmailController;
use App\Mail\VisaRenew;
use App\Mail\VacationRenew;
use App\NewSpareParts;
use App\User;
use App\UsersVacations;

class CronController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

    }


    public function renew_visa(){
        ///if($user_id){
            $infos=User::all();
            if($infos){
                foreach ($infos as $key => $value) { 
                    $end_date=$value['visa_end'];
                    $days_ago = date('Y/m/d', strtotime('-2 days', strtotime($end_date)));
                    $todays_date=date("Y/m/d");
                    if($todays_date >= $days_ago ){
                        if(!empty($end_date) && !empty($value['email'])){
                                Mail::to($value['email'])->send(new VisaRenew($end_date));
                        }
                        
                         
                    }
                }
            }
        //}
 
     }


     public function renew_vacation(){
        $infos=User::all();
        if($infos){
            foreach ($infos as $key => $value) { 
                $end_date = UsersVacations::where('id',$value['id'] )->get();
                if($end_date){
                     foreach ($end_date as $key => $value) {
                        $vacation_end_date=$value['end_date'];
                        $vacation_resume_date=$value['resume_date'];
                     }
                }
                 //echo $visa_end_date; die();
                if(!empty($vacation_end_date) && !empty($value['email']) && !empty($vacation_resume_date)){
                    Mail::to($value['email'])->send(new VacationRenew($vacation_end_date,$vacation_resume_date));
                }

            }
           
        }
        
 
     }

    
}
