<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model {

    protected $table = 'user_expense';
    protected $fillable = [
        'user_id', "expense_type", "exp_date", "amount", "vendor", "note", "user_account"
    ];

}
