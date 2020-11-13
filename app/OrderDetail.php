<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    //
    protected $fillable = ['id', 'code', 'quantity', 'discount', 'status', 'total', 'dish_Id',
        'order_Id', 'updated_at'];
}
