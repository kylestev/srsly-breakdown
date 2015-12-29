<?php

namespace Srsly\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Srsly\Server;

class ImportIps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'srsly:import
                            {path : Must be in storage directory.}
                            {--year= : Year identifier.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports a JSON file into the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $path = storage_path($this->argument('path'));
        $year = $this->option('year', 2015);

        $records = $this->readRecords($path);

        $bar = $this->output->createProgressBar($records->count());

        foreach ($records as $record) {
            try {
                Server::create([
                    'ip' => $record->ip,
                    'port' => $record->port,
                    'year' => $year,
                ]);
            } catch (QueryException $e) {
                // duplicate entry.
            }

            $bar->advance();
        }

        $bar->finish();
    }

    private function readRecords($path)
    {
        $records = json_decode(file_get_contents($path));
        return collect($records);
    }
}
