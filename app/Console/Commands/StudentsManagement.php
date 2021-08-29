<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customs\ManageStudents;

class StudentsManagement extends Command
{

    use ManageStudents;    

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add student login command';

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