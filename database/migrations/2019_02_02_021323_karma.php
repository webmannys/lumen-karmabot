<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Karma extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creates the karma table
      Schema::create('karma', function (Blueprint $table) {
        $table->increments('id');
        $table->string('handle', 64)->nullable(FALSE)->default('');
        $table->integer('points')->nullable(FALSE)->default(0);
        $table->unique('handle');
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
        // Drop karma table
      Schema::drop('karma');
    }
}
