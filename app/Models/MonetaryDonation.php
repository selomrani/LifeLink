<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonetaryDonation extends Model
{
    protected $fillable = ['user_id', 'amount', 'post_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(BloodRequestPost::class);
    }
}
