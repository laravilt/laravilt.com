<?php

namespace App\Console\Commands;

use App\Models\Documentation;
use App\Services\DocumentationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncDocsCommand extends Command
{
    protected $signature = 'docs:sync {--force : Force re-sync all documentation (clears existing)}';

    protected $description = 'Sync documentation from GitHub laravilt/laravilt repository';

    public function handle(DocumentationService $docs): int
    {
        // Force option clears all existing docs and cache
        if ($this->option('force')) {
            $this->info('Clearing existing documentation...');
            Documentation::query()->delete();
            Cache::forget('docs_navigation');
            $this->info('Cleared.');
        }

        $this->info('Syncing documentation from GitHub...');

        $synced = $docs->syncFromGithub();

        // Clear navigation cache after sync
        Cache::forget('docs_navigation');

        if (empty($synced)) {
            $this->info('Documentation is already up to date.');
        } else {
            $this->info('Synced ' . count($synced) . ' documentation pages:');
            foreach ($synced as $path) {
                $this->line("  - {$path}");
            }
        }

        $this->info('Done. Run "php artisan cache:clear" if you still see stale content.');

        return Command::SUCCESS;
    }
}
