<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'code', 'name', 'status', 'updated_at', 'sex', 'phone', 'dateOfBirth', 'Hometown_Id', 'address','avatarUrl' , 'email'
    ];
}
