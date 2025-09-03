<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

    protected $fillable = [
        'user_id', 'cust_name', 'civil_id', 'is_company', 'nationality', 'address', 'phone', 'mobile', 'mobile_three', 'fax', 'notes'
    ];

    public function nationality_name() {
        return $this->hasOne('App\Nationality', 'id', 'nationality');
    }

}
