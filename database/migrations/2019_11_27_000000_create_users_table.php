<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('patronymic');
            $table->string('phone', 12);
            $table->integer('passport_number');
            $table->integer('passport_series');
            $table->string('login')->unique();
            $table->string('password');
            $table->string('email');
            $table->bigInteger('employee_id')->index()->nullable()->unsigned();
            $table->foreign('employee_id')->references('id')
                ->on('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->string('api_token', 60)->nullable();
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
        Schema::dropIfExists('users');
    }
}
