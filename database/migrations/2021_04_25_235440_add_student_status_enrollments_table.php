<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentStatusEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('student_status')->nullable()->after('section');
            $table->string('payment_mode')->nullable()->after('student_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            //
        });
    }
}
