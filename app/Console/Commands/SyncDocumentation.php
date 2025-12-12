<?php

namespace App\Console\Commands;

use App\Services\DocumentationService;
use Illuminate\Console\Command;

class SyncDocumentation extends Command
{
    protected $signature = 'docs:sync';

    protected $description = 'Sync documentation with the git repository';

    public function handle(DocumentationService $docs): int
    {
        $this->info('Syncing documentation...');

        $docs->syncWithGit();

        $this->info('Documentation synced successfully!');

        return Command::SUCCESS;
    }
}
