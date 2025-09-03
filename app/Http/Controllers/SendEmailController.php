<?php

namespace App\Http\Controllers;

// use Session;
 use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Mail\CustomerMail;
class SendEmailController extends Controller
{
    public static function sendToMailCustomerDetails($data)
    { 
       // print_r($request) ; die();
       /* Mail::send($data['view'], $data, function ($message) {
          $message->from('testappristine@gmail.com', 'Appristine Tech');
          $message->to($data['email']);
          $message->subject($data['subject']);
      });*/
      //print_r($data); die();
       Mail::to($data['email'])->send(new CustomerMail($data));
            //Session::flash('message', "Thank you for contacting us we will get back to you soon.");
           // return '1';
        

         /*Mail::to(ADMIN_MAIL)->send(new ContactUs($request->all()));
            Session::flash('message', "Thank you for contacting us we will get back to you soon.");
            return redirect()->back();*/
        
    

       //return "<scrip>alert('Successfull sending a email ');</script>";
    }

     /*public static function sendToMailAllJobCardDetails($request){
        
        Mail::raw( $view, function($data){
          $data->to($request['email'], 'deeksha');
          $data->subject($request['subject']);
          $data->from('deeksha@appristine.in','Appristine Tech');
          $data->attach($view);
        });
       return "<scrip>alert('Successfull sending a email ');</script>";
     }*/
}
