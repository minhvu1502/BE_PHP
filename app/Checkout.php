<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $fillable = ['id', 'firstName', 'lastName', 'email', 'phone', 'address', 'paymentType', 'cart', 'total', 'created_at', 'updated_at'];
}
