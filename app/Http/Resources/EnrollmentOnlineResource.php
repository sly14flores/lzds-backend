<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentOnlineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,            
            "student_id" => $this->student_id,
            "grade" => $this->grade,
            "student_status" => $this->student_status,
            "payment_mode" => $this->payment_mode,
            "payment_method" => $this->payment_method,
            "down_payment" => $this->down_payment,
            "enrollment_school_year" => $this->enrollment_school_year,
            "enrollment_date" => $this->enrollment_date,
            "registered_online" => $this->registered_online,
            "enrollment_uiid" => $this->enrollment_uiid,
            "enrollee_rn" => $this->enrollee_rn,
            "origin" => $this->origin,
            "answers" => $this->questionnaire
        ];
    }
}
