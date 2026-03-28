<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model
{
    protected $fillable = ['can_give_to', 'can_receive_from', 'name'];

    protected $casts = [
        'can_give_to' => 'array',
        'can_receive_from' => 'array',
    ];
}
