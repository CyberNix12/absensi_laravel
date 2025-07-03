<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'name',
        'phone',
        'email',
        'profile_picture',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    protected $appends = [
        'profile_picture_asset',
    ];

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function pengajuanIzin()
    {
        return $this->hasMany(PengajuanIzin::class);
    }

    public function getProfilePictureAssetAttribute()
    {
        if ($this->profile_picture && file_exists(public_path('storage/'.$this->profile_picture))) {
            return asset('storage/'.$this->profile_picture);
        }

        return 'https://static-00.iconduck.com/assets.00/profile-default-icon-512x511-v4sw4m29.png';
    }
}