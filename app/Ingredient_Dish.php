<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ingredient_Dish extends Model
{
    protected $table = 'ingredient_dishes';
   protected $fillable = ['id', 'code', 'quantity', 'status', 'ingredient_Id', 'dish_Id', 'updated_at'];
}

