<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Gallery_img extends Model {
    
    use SoftDeletes;
    protected $table = "gallery_img";
    public $primaryKey = "id";
    protected $fillable = [
        "image"
    ];

    protected $dates = ['deleted_at'];

}
