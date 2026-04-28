<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{

    protected $fillable = [
        'donor_id',
        'blood_request_post_id',
        'status'
    ];


    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function post()
    {
        return $this->belongsTo(BloodRequestPost::class, 'blood_request_post_id');
    }
}
