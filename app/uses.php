<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class uses extends Model
{
    protected $fillable = [
        'id', 'code', 'name', 'status', 'updated_at', 'description'
    ];
}
