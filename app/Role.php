<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    //public $timestamps = false;
    protected $fillable = [
        "slug", "name", "permission_slug"
    ];

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }

}
