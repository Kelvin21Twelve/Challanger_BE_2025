<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model {

    protected $table = 'supplier_payment';
    protected $fillable = [
       "name", "payment_mode", "amount", "cheque_no"
    ];

}
