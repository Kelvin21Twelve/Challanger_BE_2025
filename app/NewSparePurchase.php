<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewSparePurchase extends Model {

    protected $table = 'new_spare_purchase';
    protected $fillable = [
       "date", "inv_no", "inv_type", "supplier_name", "note","item_code","item_name","quantity","price","balance"
    ];

}
