<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCard extends Model {

    protected $fillable = [
        "user_id", "job_no", "cab_no", "customer", 'customer_id', "phone", "vehicle", "view", "type", "model", "color", "plate_no", "kilo_meters", "status",
        "approved", "delivery_date", "employee_responsible", "notes", "requested_parts", "returned", "warranty",
        "warranty_days", "entry_date", "entry_time", "lock_card", "is_delete"
    ];

    public function customer_details() {
        return $this->hasOne('App\Customer', 'id', 'customer_id','is_delete');
    }

    public function vehicle_details() {
        return $this->hasOne('App\Vehicle', 'id', 'vehicle');
    }

    public function car_view() {
        return $this->hasOne('App\CarMake', 'id', 'car_view');
    }
    public function job_card_calculation() {
        return $this->hasOne('App\JobCardsCalculation', 'job_id', 'id');
    }

}
