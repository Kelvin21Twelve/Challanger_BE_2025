<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemUser extends Model {
	
    protected $table = 'system_user';
    protected $fillable = [
        'name', "email", "max_desc", "labour_desc", "department", "is_active"
    ];

}
