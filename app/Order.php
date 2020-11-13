<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = ['id', 'code', 'name', 'total', 'orderDate', 'useDate', 'status', 'table_Id', 'customer_Id','employee_Id' , 'updated_at'];
}
