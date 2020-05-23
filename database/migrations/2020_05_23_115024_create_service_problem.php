<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceProblem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_problem', function (Blueprint $table) {
            $table->bigIncrements('service_id')->unsigned();
            $table->bigIncrements('problem_id')->unsigned();

            $table->foreign('service_id')->references('id')->on('services')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('problem_id')->references('id')->on('problems')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->index(['service_id', 'problem_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_problem');
    }
}
