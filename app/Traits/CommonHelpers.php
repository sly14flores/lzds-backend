<?php

namespace App\Traits;

use App\Models\SchoolYear;
use App\Models\GradeLevel;

trait CommonHelpers {

    public function currentSy()
    {
        $school_year = SchoolYear::where('is_current',true)->first();

        return $school_year->id;
    }

    public function _currentSy()
    {
        $school_year = SchoolYear::where('is_current',true)->first();

        return $school_year->school_year;
    }

    public function currentSyYear()
    {
        $school_year = SchoolYear::where('is_current',true)->first();

        $sy = explode('-',$school_year->school_year);

        return $sy[0];
    }

    private function downPayments()
    {
        return collect([
            ['level'=>1,'amount'=>8500], # Nursery
            ['level'=>2,'amount'=>8500], # Kinder
            ['level'=>3,'amount'=>10500], # Grade 1
            ['level'=>4,'amount'=>10500], # Grade 2
            ['level'=>5,'amount'=>10500], # Grade 3
            ['level'=>6,'amount'=>10700], # Grade 4
            ['level'=>7,'amount'=>10700], # Grade 5
            ['level'=>8,'amount'=>10700], # Grade 6
            ['level'=>9,'amount'=>10000], # Grade 7
            ['level'=>10,'amount'=>10000], # Grade 8
            ['level'=>11,'amount'=>10000], # Grade 9
            ['level'=>12,'amount'=>10000], # Grade 10
            ['level'=>13,'amount'=>10000], # Grade 11
            ['level'=>14,'amount'=>10000], # Grade 12
        ]);
    }

    private function getDownPayment($level) {

        $down_payments = $this->downPayments();
        $down_payment = $down_payments->where('level',$level)->first();

        return $down_payment['amount'];

    }

    private function levelDescription($id) {

        $level = GradeLevel::find($id);

        return $level->description;

    }

    private function discounts() {

        return collect([
            ['id' => 1, 'name' => 'w/Highest Honors', 'percentage' => .5],
            ['id' => 2, 'name' => 'w/High Honors', 'percentage' => .25],
            ['id' => 3, 'name' => 'w/Honors', 'percentage' => .10],
            ['id' => 4, 'name' => '3 Siblings: Youngest', 'percentage' => .10],
            ['id' => 5, 'name' => '4 - 5 Siblings: Youngest', 'percentage' => 1],       
        ]);

    }

    private function getDiscount($id) {

        $discounts = $this->discounts();

        $discount = $discounts->where('id',$id)->first();

        return $discount;

    }

}