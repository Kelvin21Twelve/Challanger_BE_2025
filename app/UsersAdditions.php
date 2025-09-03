<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersAdditions extends Model {

    protected $table = 'users_additions';
    public $primaryKey = "id";
    protected $fillable = [
        'user_id', 'type','start_date', 'end_date', 'amount','description', 'is_delete'
    ];
}
