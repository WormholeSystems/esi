<?php

namespace NicolasKion\ESI\Commands;

use Illuminate\Console\Command;

class ESICommand extends Command
{
    public $signature = 'ws-esi';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
