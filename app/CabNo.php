<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CabNo extends Model {

    protected $fillable = [
        "cab_no", "job_id", "job_status"
    ];

    public function job_card() {
        return $this->hasOne('App\JobCard', 'id', 'job_id')->orderBy("status");
    }

}
