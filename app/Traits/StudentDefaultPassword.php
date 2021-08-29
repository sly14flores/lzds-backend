<?php

namespace App\Traits;

use App\Models\Student;

use Illuminate\Support\Facades\Hash;

trait StudentDefaultPassword {

  public function isPasswordDefault($id,$password)
  {

    $student = Student::find($id);

    $lastname = strtoupper($student->lastname);
    $dob = str_replace("-","",$student->date_of_birth);
    $pw = $lastname.$dob;    

    return Hash::check($pw,$password);

  }

}