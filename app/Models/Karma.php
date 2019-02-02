<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karma extends Model {

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'handle', 'points',
  ];

  protected $attributes = [
    'points' => 0,
  ];

  protected $table = 'karma';

}
