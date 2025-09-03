<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

    protected $table = 'transaction';
    protected $fillable = [
       "from_acc_no", "to_acc_no", "date", "amount", "from_acc_name","to_acc_name","type","description"
    ];

}
