<?php

namespace App\Models;

use Filament\Panel;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Facades\Storage;
use Filament\Models\Contracts\HasAvatar;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasMedia // MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use InteractsWithMedia;
    use HasUuids, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar_url',
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

    public function getFilamentName(): string
    {
        return $this->username;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // return str_ends_with($this->email, '@aech.com') && $this->hasVerifiedEmail();
        return true;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        // return $this->avatar_url ? Storage::url($this->avatar_url) : null ;
        return $this->getMedia('avatars')?->first()?->getUrl() ?? $this->getMedia('avatars')?->first()?->getUrl('thumb') ?? Storage::url($this->avatar_url) ?? null;
    }

    public function registerMediaConversions(Media|null $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 300, 300)
            ->nonQueued();
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
