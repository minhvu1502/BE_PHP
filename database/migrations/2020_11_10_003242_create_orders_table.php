<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->dateTime('orderDate');
            $table->dateTime('useDate');
            $table->double('total');
            $table->boolean('status');
            $table->foreignId('table_Id')->constrained('tables')->onDelete('cascade');
            $table->foreignId('customer_Id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('employee_Id')->constrained('employees')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}
