<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlackUser extends Model {

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'uid',
    'name',
    'real_name',
    'profile_real_name',
    'profile_real_name_normalized',
    'profile_display_name',
    'profile_display_name_normalized',
  ];

  protected $attributes = [
    'name' => '',
    'real_name' => '',
    'profile_real_name' => '',
    'profile_real_name_normalized' => '',
    'profile_display_name' => '',
    'profile_display_name_normalized' => '',
  ];

  protected $table = 'slack_users';

}
