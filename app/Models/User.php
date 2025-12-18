<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Concerns\HasTeams;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravilt\Auth\Concerns\LaraviltUser;
use Laravilt\Panel\Contracts\HasDefaultTenant;
use Laravilt\Panel\Contracts\HasTenants;
use Laravilt\Panel\Panel;

class User extends Authenticatable implements HasTenants, HasDefaultTenant
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LaraviltUser, HasTeams;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_demo',
        'demo_expires_at',
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
            'is_demo' => 'boolean',
            'demo_expires_at' => 'datetime',
        ];
    }

    /**
     * Check if user is a demo user.
     */
    public function isDemo(): bool
    {
        return $this->is_demo === true;
    }

    /**
     * Check if demo session has expired.
     */
    public function isDemoExpired(): bool
    {
        if (!$this->is_demo || !$this->demo_expires_at) {
            return false;
        }
        return $this->demo_expires_at->isPast();
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
