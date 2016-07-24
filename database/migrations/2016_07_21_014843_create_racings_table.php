<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRacingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('racings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('periodNumber');
            $table->timestamp('awardTime');
            $table->string('awardNumbers', 20);
            $table->boolean('expired')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('racings');
    }
}
