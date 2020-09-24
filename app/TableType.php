<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TableType extends Model
{
    protected $table = 'TableTypes';
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'code', 'name', 'status', 'updated_at', 'description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
