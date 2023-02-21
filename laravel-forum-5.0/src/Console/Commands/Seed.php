<?php

namespace TeamTeaTime\Forum\Console\Commands;

use Illuminate\Console\Command;
use TeamTeaTime\Forum\Database\Seeders\ForumSeeder;

class Seed extends Command
{
    protected $signature = 'forum:seed';

    protected $description = 'Seed the forum tables with example content.';

    public function handle()
    {
        (new ForumSeeder)->run();
        $this->info('Done!');
    }
}
