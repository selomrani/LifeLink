<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodREquestPost extends Model
{
    protected $fillable = [
        'blood_type',
        'description',
        'location',
        'needed_by',
        'user_id',
        'status',
        'media_path',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
