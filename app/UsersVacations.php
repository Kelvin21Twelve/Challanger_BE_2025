<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersVacations extends Model {

    protected $table = 'users_vacations';
    public $primaryKey = "id";
    protected $fillable = [
        'user_id', 'type','start_date', 'resume_date', 'end_date', 'balance','description', 'is_delete'
    ];
}
