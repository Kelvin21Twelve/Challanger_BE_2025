<?php
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Mail\SendMail;
use App\User;


    function GetLoggedUserId()
    {

        if (!empty(Auth::user()) && Auth::user()->id) {
            $user = Auth::user();

            return $user;
        }
    }

    function en_de_crypt($string, $action = 'e')
    {

        $secret_key = 'a1s3eskm3fssdddg2x3q32xr19w3';
        $secret_iv = 'a1snsdd5nrer19w3kkjlpf9llkw22x';
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {

            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    function GetRoute()
    {
        return Route::currentRouteName();
    }


    function sendToMail(Request $get)
    {
        $this->validate($get,[
            "email" => "required",
            "subject" => "required",
            "message" => "required",
            "view" => "required",
         ]);
            $email = $get->email;
            $subject = $get->subject;
            $message = $get->message;
            $view = $get->view;
            Mail::to($email)->send(new SendMail($subject,$message,$view));
            // Session::flash("Success");
            return exit();
    }

    function attachment_email()
    {
        $data = array('name'=>"Virat Gandhi");

        Mail::send('mail', $data, function($message)
        {
            $message->to('abc@gmail.com', 'Tutorials Point')->subject('Laravel Testing Mail with Attachment');
            $message->attach('C:\laravel-master\laravel\public\uploads\image.png');
            $message->attach('C:\laravel-master\laravel\public\uploads\test.txt');
            $message->from('xyz@gmail.com','Virat Gandhi');
        });
        echo "Email Sent with attachment. Check your inbox.";
     }




?>