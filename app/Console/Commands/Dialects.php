<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use App\Models\Dialect;

class Dialects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dialects:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import dialects from vsmart';

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

        $response = Http::get('https://enroll.vsmart.ph/School/GetLanguages?countrycode=PH');

        $dialects = ($response->json())['object'];

        $bar = $this->output->createProgressBar(count($dialects)-1);
        $bar->start();
        foreach ($dialects as $i => $dialect) {

            if ($i == 0) continue;

            $import = new Dialect;
            $import->fill(['name'=>$dialect['text']]);
            $import->save();

            $bar->advance();

        }

        $bar->finish();

        return 0;
    }
}
