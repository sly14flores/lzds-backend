<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Mail\ParentalConsent;
use Illuminate\Support\Facades\Mail;

class TestMailer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailer:test {mailer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test mailer';

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
        $mailer = $this->argument('mailer');
        $email = "sly@christian.com.ph";

        Mail::mailer("mailjet")->to($email)->send(new ParentalConsent());

        return 0;
    }
}
