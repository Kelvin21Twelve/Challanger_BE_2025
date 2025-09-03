<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolesPermission extends Model {

    //public $timestamps = false;
    protected $fillable = [
        "role_id", "permission_id"
    ];

}
