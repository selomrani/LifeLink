<?php

namespace App\Models;

// use Database\Factories\BloodREquestPostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequestPost extends Model
{
    use HasFactory;

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
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $table = 'blood_request_posts';

    // protected static function newFactory()
    // {
    //     return BloodREquestPostFactory::new();
    // }
    public function donations()
    {
        return $this->hasMany(MonetaryDonation::class, 'post_id');
    }
}
