<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserTargetReport extends Mailable {

    use Queueable,
        SerializesModels;

    public $main_array;

    /**
     * Create a new message instance.
     *S
     * @return void
     */
    public function __construct($main_array) {
        $this->main_array = $main_array;
        
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->from('challenger-garage-sw@challenger-co.com', 'test')
                        ->view('report/print_users_target_report')
                        ->subject('User Target Report');
    }

}
