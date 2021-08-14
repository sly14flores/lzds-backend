<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customs\ManageStaffs;

class StaffsManagement extends Command
{

    use ManageStaffs;    

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staffs:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add staff login command';

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
     * @return int
     */
    public function handle()
    {

        $this->start();

        return 0;

    }
}
