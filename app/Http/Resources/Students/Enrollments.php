<?php

namespace App\Http\Resources\Students;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

class Enrollments extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'school_year' => $this->school_year->school_year,
            'school_id' => $this->school_id,
            'grade_level' => $this->level->description,
            'date_enrolled' => Carbon::parse($this->system_log)->format("F j, Y"),
        ];
    }
}
