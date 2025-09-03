<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UNPOSTSparePurchase extends Mailable {

    use Queueable,
        SerializesModels;

    public $main_array, $from_date ,$to_date;

    /**
     * Create a new message instance.
     *S
     * @return void
     */
    public function __construct($main_array,$from_date,$to_date) {
        $this->main_array = $main_array;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->from('challenger-garage-sw@challenger-co.com', 'test')
                        ->view('report/print_unpost_sp_part_purchase')
                        ->subject('Spare Part Sales Report');
    }

}
