<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Karma extends Model {
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'handle', 'karma',
  ];

}
