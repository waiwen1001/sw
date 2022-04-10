<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
  protected $table = 'customers';
  protected $fillable = [
    'first_name',
    'last_name',
    'gender',
    'date_of_birth',
    'contact_number',
    'email'
  ];
}
