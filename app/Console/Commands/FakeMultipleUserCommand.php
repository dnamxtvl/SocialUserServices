<?php

namespace App\Console\Commands;

use App\Data\Models\User;
use Illuminate\Console\Command;
use Throwable;

class FakeMultipleUserCommand extends Command
{
    const numberUser = 1000000;
    const chunk = 500;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fake-multiple-user-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $bar = $this->output->createProgressBar(self::numberUser / self::chunk);
        $bar->start();

        try {
            for ($i = 0; $i < self::numberUser / self::chunk; $i++) {
                User::factory(self::chunk)->create();
                $bar->advance();
            }
            $bar->finish();
        } catch (Throwable $th) {
            $bar->finish();
            $this->error($th);
        }
    }
}
