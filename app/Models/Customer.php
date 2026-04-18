<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['account_id', 'name', 'phone', 'phone_2', 'city', 'address', 'referred_by'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
