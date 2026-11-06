<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\Contracts\OAuthenticatable;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Passport;

class User extends Authenticatable implements OAuthenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Available roles.
     */
    const ROLE_ADMIN_WEBSITE = 'admin_website';

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
     * Check if user has one of the given roles.
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;
        return in_array($this->role, $roles);
    }

    /**
     * Akun admin dipakai bersama: saat password diganti, semua koneksi connector
     * Claude (token OAuth) ikut dicabut sehingga harus login ulang dengan password baru.
     */
    protected static function booted(): void
    {
        static::updated(function (User $user) {
            if ($user->wasChanged('password')) {
                $user->revokeOAuthTokens();
            }
        });
    }

    public function revokeOAuthTokens(): void
    {
        try {
            $tokenIds = $this->tokens()->pluck('id');
            if ($tokenIds->isEmpty()) {
                return;
            }
            Passport::token()->newQuery()->whereIn('id', $tokenIds)->update(['revoked' => true]);
            Passport::refreshToken()->newQuery()->whereIn('access_token_id', $tokenIds)->update(['revoked' => true]);
        } catch (\Throwable $e) {
            report($e); // jangan gagalkan ganti password
        }
    }
}
