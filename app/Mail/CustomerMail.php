<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerMail extends Mailable {

    use Queueable,
        SerializesModels;

    public $main_array;

    /**
     * Create a new message instance.
     *
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
                        ->view('report/print_customer_detail')
                        ->subject('Customer Details Report');
    }

}
