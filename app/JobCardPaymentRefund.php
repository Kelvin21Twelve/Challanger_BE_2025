<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCardPaymentRefund extends Model {

    protected $fillable = [
        "job_id", "amount", "refund_by", "auth_code", "remaining", "print_invoice", "print_voucher"
    ];

}
