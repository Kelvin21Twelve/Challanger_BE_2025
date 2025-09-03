<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model {

    protected $fillable = [
        'user_id', "account_name_en", "account_name_ar", "account_code", "super_account", "opening_balance", "balance", "printable", "is_bank_account", "is_cash_account"
    ];

}
