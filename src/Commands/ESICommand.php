<?php

declare(strict_types=1);

namespace WormholeSystems\ESI\Commands;

use Illuminate\Console\Command;

final class ESICommand extends Command
{
    public $signature = 'ws-esi';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
