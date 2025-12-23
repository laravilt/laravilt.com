<?php

namespace App\Providers\Laravilt;

use App\Models\Team;
use Laravilt\AI\Builders\AIProviderBuilder;
use Laravilt\AI\Builders\GlobalSearchBuilder;
use Laravilt\AI\Enums\OpenAIModel;
use Laravilt\AI\Providers\OpenAIProvider;
use Laravilt\Auth\Builders\SocialProviderBuilder;
use Laravilt\Auth\Builders\TwoFactorProviderBuilder;
use Laravilt\Auth\Drivers\EmailDriver;
use Laravilt\Auth\Drivers\SocialProviders\GitHubProvider;
use Laravilt\Auth\Drivers\SocialProviders\GoogleProvider;
use Laravilt\Auth\Drivers\TotpDriver;
use Laravilt\Panel\FontProviders\GoogleFontProvider;
use Laravilt\Panel\Panel;
use Laravilt\Panel\PanelProvider;

class AdminPanelProvider extends PanelProvider
{
    /**
     * Configure the panel.
     */
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->default()
            ->brandName("Laravilt")
            ->tenant(Team::class, 'team', 'slug')
            ->tenantProfile()
            ->tenantRegistration()
            ->font(
                GoogleFontProvider::make('IBM Plex Sans Arabic')
                    ->weights([400, 500, 600, 700])
            )
            ->discoverAutomatically()
            ->login()
            ->registration()
            ->passwordReset()
            ->magicLinks()
            ->profile()
            ->passkeys()
            ->connectedAccounts()
            ->sessionManagement()
            ->apiTokens()
            ->localeTimezone()
            ->databaseNotifications()
            ->twoFactor(builder: function (TwoFactorProviderBuilder $builder) {
                $builder->provider(TotpDriver::class);
            })
            ->socialLogin(function (SocialProviderBuilder $builder) {
                $builder->provider(GitHubProvider::class, fn (GitHubProvider $p) => $p->enabled());
            })
            ->globalSearch(function (GlobalSearchBuilder $search) {
                $search->enabled()->limit(5)->debounce(300);
            })
            ->aiProviders(function (AIProviderBuilder $ai) {
                $ai->provider(OpenAIProvider::class, fn (OpenAIProvider $p) => $p->model(OpenAIModel::GPT_4O_MINI))
                   ->default('openai');
            })
            ->middleware(['web', 'auth'])
            ->authMiddleware(['auth']);
    }
}
