<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralLedger extends Model {

    protected $table = 'general_ledger';
    protected $fillable = [
       "acc_no", "date", "credit", "debit", "description","type"
    ];

}
