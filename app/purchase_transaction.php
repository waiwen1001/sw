<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class purchase_transaction extends Model
{
  protected $table = 'purchase_transaction';
  protected $fillable = [
    'customer_id',
    'total_spent',
    'total_saving',
    'transaction_at',
  ];
}
