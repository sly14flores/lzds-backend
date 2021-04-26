<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use App\Models\IndigenousGroup;

class IndigenousGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indigenous:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import indigenous groups from vsmart';

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
        $response = Http::get('https://enroll.vsmart.ph/School/GetIndigentGroup?countrycode=PH');

        $groups = ($response->json())['object'];

        $bar = $this->output->createProgressBar(count($groups)-1);
        $bar->start();
        foreach ($groups as $i => $group) {

            if ($i == 0) continue;

            $import = new IndigenousGroup;
            $import->fill(['name'=>$group['text']]);
            $import->save();

            $bar->advance();

        }

        $bar->finish();        

        return 0;
    }
}
