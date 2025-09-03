<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersDocuments extends Model {

    protected $table = 'users_documents';
    public $primaryKey = "id";
    protected $fillable = [
        'user_id', 'document', 'document_description', 'is_delete'
    ];

}
