<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Hash;

use App\Models\Student;
use App\Models\User;

class StudentsCredentials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:credentials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import students for login credentials';

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
        $students = Student::where('lrn','!=',0)->get();

        $bar = $this->output->createProgressBar(count($students));

        $this->info("Importing students credentials...");
        $bar->start();

        foreach ($students as $student) {

            $check = User::where('student_id',$student->id)->first();

            if (is_null($check)) {
                $user = new User;
                $user->lrn = $student->lrn;
                $user->student_id = $student->id;
                $user->email = $student->email_address;

                $lastname = strtoupper($student->lastname);
                $dob = str_replace("-","",$student->date_of_birth);
                $pw = $lastname.$dob;

                $user->password = Hash::make($pw);
                $user->save();
            }

            $bar->advance();

        }

        $bar->finish();

        return 0;
    }
}
