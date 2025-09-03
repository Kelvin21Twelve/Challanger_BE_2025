<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersAbsences extends Model {

    protected $table = 'users_absences';
    public $primaryKey = "id";
    protected $fillable = [
        'user_id', 'on_date','description', 'is_delete'
    ];
}
