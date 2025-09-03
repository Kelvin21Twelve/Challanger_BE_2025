<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\helper;
class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    //public $data;    
    public $mess;
    public $vw;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $view)
    {
        // $this->data = $data;        
        $this->mess = $message;
        $this->vw = $view;
    }
    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $e_message = $this->mess;        
        $e_view = $this->vw;
        // return $this->view('emails.feedback')->with('data', $this->data);
        return $this->view($e_view, compact("e_message"));
    }
}