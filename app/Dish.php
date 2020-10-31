<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    protected $fillable = [
        'id', 'code', 'name','make' ,'petition','total' , 'status', 'updated_at', 'dishType_Id', 'use_Id'
    ];
}
