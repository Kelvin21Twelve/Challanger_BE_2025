<?php

namespace App\Http\Controllers;
use FarhanWazir\GoogleMaps\GMaps;
use App\ContactUs;
use Illuminate\Http\Request;
use App\Mail\ContactUsMail;
use Mail;

class ContactUsController extends Controller
{
    public function __construct(Request $request) {
       $this->edata =  new \stdClass();
    }

    public function store(Request $request) {
      if($request->all()){
        $ContactUs = new ContactUs();
        $ContactUs->fill($request->all());
        $ContactUs->save();
        $edata = $this->edata;
        $edata->message  ="<h1>Welcome to challenger</h1>";
        $edata->message .= "<h1>Dear ".$ContactUs->name.",</h1>"; 
        $edata->message .= "<h1>Thank You for showing interest in challenger. We will get back to you shortly! </h1>";              
        $edata->message .= "<p class='sub'>If you have any questions. Please contact administrator.</p>";
        $edata->message .= "<p>Regards,<br>Challenger</p>";
        $edata->subject ="Thank for showing interest";
        //print_r($edata);
        //Mail::to($ContactUs->email)->send(new ContactUsMail($edata));

        $edata = $this->edata;
        $edata->message  ="<h1>New person detail:</h1>";
        $edata->message .= "<p>Name : ".$ContactUs->name."</p>"; 
        $edata->message .= "<p>Email : ".$ContactUs->email."</p>";
        $edata->message .= "<p>Message : ".$ContactUs->message."</p>";               
        $edata->subject ="New person contact us";
        //print_r($edata);
        Mail::to("info@challenger-co.com")->send(new ContactUsMail($edata));

        return response()->json(['success' => TRUE,'op'=>'create','msg_type'=>'success','msg'=>'Conatct saved successfully!','redirect_url'=>'/pages/contact-us']);
      }else{
        
        return response()->json(['success' => FALSE,'op'=>'create','msg_type'=>'error','msg'=>'Failed!','redirect_url'=>'/pages/contact-us']);
      }
        
         
    }

    public function view(){
      return view('pages.contact-us');
    }
}
