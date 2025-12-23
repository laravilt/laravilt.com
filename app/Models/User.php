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
use Illuminate\Support\Facades\Http;
use Laravilt\Auth\Concerns\LaraviltUser;
use Laravilt\Panel\Contracts\HasDefaultTenant;
use Laravilt\Panel\Contracts\HasTenants;
use Laravilt\Panel\Panel;

class User extends Authenticatable implements HasTenants, HasDefaultTenant
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LaraviltUser, HasTeams;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->notifyDiscordNewUser();
        });
    }

    /**
     * Send a welcome notification to Discord when a new user registers.
     */
    public function notifyDiscordNewUser(): void
    {
        $webhookUrl = 'https://discord.com/api/webhooks/1451682311501381923/74LuzVXgV0kaxWPtWOwU_Qfpe3UhBTA_2a_aTBvpRuKcRpFY-tThDJFezZqxB6zqWZDp';

        Http::post($webhookUrl, [
            'embeds' => [
                [
                    'title' => '👋 New User Registered!',
                    'description' => "Welcome **{$this->name}** to Laravilt Demo!",
                    'color' => 0x04bdaf,
                    'fields' => [
                        [
                            'name' => 'Email',
                            'value' => $this->email,
                            'inline' => true,
                        ],
                        [
                            'name' => 'Registered At',
                            'value' => $this->created_at->format('M d, Y H:i'),
                            'inline' => true,
                        ],
                    ],
                    'footer' => [
                        'text' => 'Laravilt Demo',
                    ],
                    'timestamp' => now()->toIso8601String(),
                ],
            ],
        ]);
    }

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
