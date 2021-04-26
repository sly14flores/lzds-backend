<?php

namespace App\Traits;

use App\Models\SchoolYear;

trait CommonHelpers {

    public function currentSy()
    {
        $school_year = SchoolYear::where('is_current',true)->first();

        return $school_year->id;
    }

}