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


    protected $table = 'blood_request_posts';

    // protected static function newFactory()
    // {
    //     return BloodREquestPostFactory::new();
    // }
// The person who wrote the post
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // All the users who volunteered to donate to this post
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}
