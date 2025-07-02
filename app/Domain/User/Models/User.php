<?php

namespace Domain\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Filament\Panel;
use Domain\Shared\Traits\GetTableName;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Database\Factories\Domain\User\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id Primary key
 * @property string $name User's name
 * @property string $email User's email address
 * @property string $password User's password
 * @property Carbon|null $email_verified_at Timestamp when the email was verified
 * @property string|null $remember_token Token for "remember me" functionality
 * @property-read bool $is_admin Indicates if the user is an admin based on their email
 */
class User extends Authenticatable implements FilamentUser
{
    use GetTableName;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * Determine if the user can access the given panel.
     *
     * @SuppressWarnings(UnusedFormalParameter)
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin && $this->hasVerifiedEmail();
    }

    /**
     * Determine if the user is an admin.
     */
    protected function isAdmin(): Attribute
    {
        return Attribute::make(get: function () {
            return str_ends_with($this->email, '@' . config('mail.filament_user_mail_domain'));
        });
    }
}
