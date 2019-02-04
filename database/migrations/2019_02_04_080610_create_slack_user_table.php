<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlackUserTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('slack_users', function (Blueprint $table) {
      $table->increments('id');
      $table->string('uid', 64)->unique();
      $table->string('name', 64)->nullable(FALSE)->default('')->index('name');
      $table->string('real_name', 64)->nullable(FALSE)->default('');
      $table->string('profile_real_name', 64)->nullable(FALSE)->default('');
      $table->string('profile_real_name_normalized', 64)->nullable(FALSE)->default('');
      $table->string('profile_display_name', 64)->nullable(FALSE)->default('');
      $table->string('profile_display_name_normalized', 64)->nullable(FALSE)->default('');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('slack_users');
  }
}
