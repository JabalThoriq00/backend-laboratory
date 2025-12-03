<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'nim',
        'nip',
        'phone',
        'address',
        'gender',
        'birth_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user is mahasiswa
     */
    public function isMahasiswa(): bool
    {
        return $this->role && $this->role->name === Role::MAHASISWA;
    }

    /**
     * Check if user is dosen
     */
    public function isDosen(): bool
    {
        return $this->role && $this->role->name === Role::DOSEN;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === Role::ADMIN;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return $this->role && $this->role->name === $roles;
        }

        return $this->role && in_array($this->role->name, $roles);
    }
}

