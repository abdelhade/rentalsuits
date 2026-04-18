<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['account_id', 'name', 'phone', 'city', 'address'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
