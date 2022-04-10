<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class voucher extends Model
{
  protected $table = 'vouchers';
  protected $fillable = [
    'customer_id',
    'voucher_code',
    'redeemed',
    'expired_at',
  ];
}
