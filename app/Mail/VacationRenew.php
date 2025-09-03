<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VacationRenew extends Mailable {

    use Queueable,
        SerializesModels;

    public $vacation_end_date,$vacation_resume_date;

    /**
     * Create a new message instance.
     *S
     * @return void
     */
    public function __construct($vacation_end_date,$vacation_resume_date) {
       $this->vacation_end_date = $vacation_end_date;
       $this->vacation_resume_date = $vacation_resume_date;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->from('challenger-garage-sw@challenger-co.com', 'test')
                        ->view('report/vacation_expiry')
                        ->subject('Vacation Notification');
    }

}
