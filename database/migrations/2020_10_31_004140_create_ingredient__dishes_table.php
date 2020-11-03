<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngredientDishesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingredient_dishes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->integer('quantity');
            $table->boolean('status');
            $table->foreignId('ingredient_Id')->constrained('ingredients')->onDelete('cascade');
            $table->foreignId('dish_Id')->constrained('dishes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ingredient__dishes');
    }
}
