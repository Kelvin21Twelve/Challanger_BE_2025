<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUsMail extends Mailable {

    use Queueable,
        SerializesModels;

   

    /**
     * Create a new message instance.
     *S
     * @return void
     */
    public function __construct($mdata) {
        $this->mdata = $mdata;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        
        return $this->markdown('emails.user.welcome')->subject($this->mdata->subject)->with([
            'mdata' => $this->mdata,
        ]);
                           
    }

}
