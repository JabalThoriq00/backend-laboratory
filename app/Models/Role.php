<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    // Role constants
    const MAHASISWA = 'mahasiswa';
    const DOSEN = 'dosen';
    const ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Get the users for the role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role is mahasiswa
     */
    public function isMahasiswa(): bool
    {
        return $this->name === self::MAHASISWA;
    }

    /**
     * Check if role is dosen
     */
    public function isDosen(): bool
    {
        return $this->name === self::DOSEN;
    }

    /**
     * Check if role is admin
     */
    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }
}
