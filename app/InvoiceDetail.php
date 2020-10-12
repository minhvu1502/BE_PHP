<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $fillable = [
        'id', 'code', 'name','status', 'total', 'updated_at', 'price', 'invoice_Id', 'quantity', 'discount', 'ingredient_Id'
    ];
}
