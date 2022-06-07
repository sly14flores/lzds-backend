<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use App\Mail\ParentalConsent;

class SendParentalConsent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consent:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email parental consent';

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

        $email = "sly@christian.com.ph";
        Mail::to($email)->send(new ParentalConsent());

        return 0;
    }
}
