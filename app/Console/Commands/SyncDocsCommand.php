<?php

namespace App\Console\Commands;

use App\Services\DocumentationService;
use Illuminate\Console\Command;

class SyncDocsCommand extends Command
{
    protected $signature = 'docs:sync';

    protected $description = 'Sync documentation from GitHub laravilt/laravilt repository';

    public function handle(DocumentationService $docs): int
    {
        $this->info('Syncing documentation from GitHub...');

        $synced = $docs->syncFromGithub();

        if (empty($synced)) {
            $this->info('Documentation is already up to date.');
        } else {
            $this->info('Synced ' . count($synced) . ' documentation pages:');
            foreach ($synced as $path) {
                $this->line("  - {$path}");
            }
        }

        return Command::SUCCESS;
    }
}
