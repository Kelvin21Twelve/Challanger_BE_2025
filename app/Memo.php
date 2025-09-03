<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Memo extends Model {

    protected $fillable = [
        'user_id', 'note', 'date', 'username', 'is_delete'
    ];

}
