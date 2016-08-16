<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned()->index()->default(0);
            $table->foreign('client_id')->references('id')->on('clients')
                  ->onUpdate('cascade')->onDelete('restrict');
            $table->integer('car_id')->unsigned()->index()->default(0);
            $table->foreign('car_id')->references('id')->on('cars')
                  ->onUpdate('cascade')->onDelete('restrict');
            $table->date('date_from');
            $table->date('date_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('rentals');
    }
}
