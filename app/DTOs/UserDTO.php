<?php

namespace App\DTOs;

use App\Models\User;

readonly class UserDTO
{
    public function __construct(
        public int $id,
        public string $fullName,
        public string $email,
        public string $profilePhotoUrl,
        public ?string $bloodType,
        public ?string $role,
        public string $joinedAt,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            fullName: trim($user->firstname . ' ' . $user->lastname),
            email: $user->email,
            profilePhotoUrl: $user->profile_photo_url,
            bloodType: $user->bloodType?->name,
            role: $user->role?->name,
            joinedAt: $user->created_at->format('M d, Y'),
        );
    }
}
