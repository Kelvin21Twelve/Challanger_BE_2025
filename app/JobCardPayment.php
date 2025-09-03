<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCardPayment extends Model {

    protected $fillable = [
        "job_id", "amount", "pay_by", "auth_code", "remaining", "print_invoice", "print_voucher"
    ];

}
