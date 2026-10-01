<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Available roles.
     */
    const ROLE_ADMIN_WEBSITE = 'admin_website';
    const ROLE_ADMIN_MARKETING = 'admin_marketing';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jabatan',
        'deskripsi',
        'foto',
        'sosmed_instagram',
        'sosmed_linkedin',
        'sosmed_youtube',
        'sosmed_tiktok',
        'sosmed_whatsapp',
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
        ];
    }

    /**
     * Check if user is Admin Website.
     */
    public function isAdminWebsite(): bool
    {
        return $this->role === self::ROLE_ADMIN_WEBSITE;
    }

    /**
     * Check if user is Admin Marketing/SEO.
     */
    public function isAdminMarketing(): bool
    {
        return $this->role === self::ROLE_ADMIN_MARKETING;
    }

    /**
     * Check if user has one of the given roles.
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;
        return in_array($this->role, $roles);
    }
}
