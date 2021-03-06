<?php namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class DropTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:drop-tables 
                            {--force : Disable foreign key checks}
                            {--Y|yes : Bypass confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all tables';

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

        if (!$this->option('yes') && !$this->confirm('CONFIRM DROP AL TABLES IN THE CURRENT DATABASE? [y|N]')) {
            exit('Drop Tables command aborted');
        }

        $force = (bool) $this->option('force');

        $colname = 'Tables_in_' . DB::getDatabaseName();

        $tables = DB::select('SHOW TABLES');

        $droplist = [];
        foreach($tables as $table) $droplist[] = $table->$colname;
        $droplist = implode(',', $droplist);

        DB::beginTransaction();

        // Turn off referential integrity
        if($force) DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        DB::statement("DROP TABLE $droplist");

        // Turn referential integrity back on
        if($force) DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        DB::commit();

        $this->comment(PHP_EOL."If no errors showed up, all tables were dropped".PHP_EOL);
    }
}