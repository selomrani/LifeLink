<?php

namespace App\Models;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'blood_type_id',
        'role_id',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'profile_photo_url',
        'can_donate',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function () {
            if ($this->profile_photo_path) {
                if (str_starts_with($this->profile_photo_path, 'https://')) {
                    return $this->profile_photo_path;
                }
                return 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . $this->profile_photo_path;
            }
            $fullName = trim($this->firstname . ' ' . $this->lastname);
            $name = urlencode($fullName ?: $this->email);
            return "https://ui-avatars.com/api/?name={$name}&color=7F9CF5&background=EBF4FF";
        });
    }
    public function donations()
    {
        return $this->hasMany(Donation::class, 'donor_id');
    }


    public function bloodRequestPosts()
    {
        return $this->hasMany(BloodRequestPost::class);
    }
    public function medicalCoolDown(){
        $lastDonation = $this->donations()->where('status', 'accepted')->latest()->first();
        if ($lastDonation) {
            return $lastDonation->created_at->addDays(56);
        }
        return null;
    }

    public function getCanDonateAttribute(){
        $cooldown = $this->medicalCoolDown();
        return $cooldown === null || $cooldown <= now();
    }
}
