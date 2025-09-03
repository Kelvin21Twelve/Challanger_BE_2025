<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VisaRenew extends Mailable {

    use Queueable,
        SerializesModels;

    public $visa_end_date;

    /**
     * Create a new message instance.
     *S
     * @return void
     */
    public function __construct($visa_end_date) {
       $this->visa_end_date = $visa_end_date;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->from('challenger-garage-sw@challenger-co.com', 'test')
                        ->view('report/visa_expiry')
                        ->subject('Visa Notification');
    }

}
