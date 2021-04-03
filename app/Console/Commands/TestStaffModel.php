<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestStaffModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staffs:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test staff model';

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

        $staff = User::find(1);

        var_dump($staff->created_at);
        var_dump($staff->updated_at);

        return 0;
    }
}
