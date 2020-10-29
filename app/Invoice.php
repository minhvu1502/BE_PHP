<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'code', 'name','status', 'total', 'updated_at', 'import_Day', 'employee_Id', 'provider_Id'
    ];
}
