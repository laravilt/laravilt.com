<?php

namespace App\Console\Commands;

use App\Models\Documentation;
use App\Services\DocumentationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class SyncDocsCommand extends Command
{
    protected $signature = 'docs:sync
        {--force : Force re-sync all documentation (clears existing)}
        {--local : Sync from local packages/laravilt/docs folder instead of GitHub}
        {--path= : Custom local path to sync docs from}';

    protected $description = 'Sync documentation from GitHub or local filesystem';

    public function handle(DocumentationService $docs): int
    {
        // Force option clears all existing docs and cache
        if ($this->option('force')) {
            $this->info('Clearing existing documentation...');
            Documentation::query()->delete();
            Cache::forget('docs_navigation');
            $this->info('Cleared.');
        }

        $synced = [];

        if ($this->option('local') || $this->option('path')) {
            // Sync from local filesystem
            $localPath = $this->option('path') ?: base_path('packages/laravilt/docs');

            if (!is_dir($localPath)) {
                $this->error("Local docs path not found: {$localPath}");
                return Command::FAILURE;
            }

            $this->info("Syncing documentation from local: {$localPath}");
            $synced = $docs->syncFromLocal($localPath);
        } else {
            // Sync from GitHub
            $this->info('Syncing documentation from GitHub...');
            $synced = $docs->syncFromGithub();
        }

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
