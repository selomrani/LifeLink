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

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public const COMPATIBILITY_MAP = [
        'O-' => ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'],
        'O+' => ['O+', 'A+', 'B+', 'AB+'],
        'A-' => ['A-', 'A+', 'AB-', 'AB+'],
        'A+' => ['A+', 'AB+'],
        'B-' => ['B-', 'B+', 'AB-', 'AB+'],
        'B+' => ['B+', 'AB+'],
        'AB-' => ['AB-', 'AB+'],
        'AB+' => ['AB+'],
    ];

    public function compatibleBloodTypes()
    {
        return self::COMPATIBILITY_MAP[$this->name];
    }
}
