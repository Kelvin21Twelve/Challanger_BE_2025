<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model {

    protected $table = 'expense_type';
    protected $fillable = [
        'id', "account_number", "account_name", "type"
    ];

}
