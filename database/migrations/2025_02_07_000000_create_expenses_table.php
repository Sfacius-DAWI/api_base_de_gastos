<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    public function up()
{
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->decimal('amount', 10, 2);
        $table->string('category');
        $table->text('description')->nullable();
        $table->timestamps();

        // Define la clave foránea para relacionar el gasto con un usuario
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
