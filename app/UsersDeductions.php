<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersDeductions extends Model {

    protected $table = 'users_deductions';
    public $primaryKey = "id";
    protected $fillable = [
        'user_id', 'type', 'start_date', 'end_date', 'amount', 'description', 'is_delete'
    ];

}
