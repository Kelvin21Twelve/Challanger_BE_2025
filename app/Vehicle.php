<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {

    protected $fillable = [
        'user_id', 'plate_no', 'customer', 'car_view', 'car_type', 'model', 'car_make', 'chasis_no', 'car_color', 'engine_cc', 'driver_name', 'driver_mobile', 'note','car_engine'
    ];

    public function view() {
        return $this->hasOne('App\CarMake', 'id', 'car_view');
    }

    public function type() {
        return $this->hasOne('App\CarModel', 'id', 'car_type');
    }

    public function color() {
        return $this->hasOne('App\CarColor', 'id', 'car_color');
    }

    public function agency() {
        return $this->hasOne('App\Agency', 'id', 'car_make');
    }

    public function customers() {
        return $this->hasOne('App\Customer', 'id', 'customer');
    }

    public function engine() {
        return $this->hasOne('App\CarEngine', 'id', 'car_engine');
    }

}
