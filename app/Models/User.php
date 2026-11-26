<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperadmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isPimpinan(): bool
    {
        return in_array($this->role, ['pimpinan', 'superadmin'], true);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin', 'pimpinan'], true);
    }

    public function isGuru(): bool
    {
        return in_array($this->role, ['teacher', 'guru'], true);
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function hasPermission(string $permission): bool
    {
        return $this->isAdmin() || $this->isGuru();
    }

    public function educationLevel(): ?string
    {
        return $this->education_level ?? null;
    }

    public function studentGrade(): ?string
    {
        return $this->student_grade ?? null;
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id');
    }
}
