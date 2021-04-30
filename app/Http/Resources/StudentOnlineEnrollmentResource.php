<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\SchoolYear;
use App\Models\GradeLevel;
use App\Models\Fee;
use App\Models\FeeItem;

use Carbon\Carbon;

use App\Traits\CommonHelpers;

class StudentOnlineEnrollmentResource extends JsonResource
{
    use CommonHelpers;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $recent_enrollment = $this->enrollments()->orderByDesc('system_log')->first();

        if (is_null($recent_enrollment)) { // Nursery
            $recent_level_id = null;
            $recent_level_description = null;
            $next_level_id = null;
            $next_level_description = null;
        } else {
            $recent_level = $recent_enrollment->level;            
            $recent_level_id = $recent_level->id;
            $recent_level_description = $recent_level->description;
            $next_level = GradeLevel::find($recent_level_id+1);
            $next_level_id = $next_level->id;
            $next_level_description = $next_level->description;            
        }

        $discounts = [1,2];

        return [
            "id" => $this->id,
            "lrn" => $this->lrn,
            "lastname" => $this->lastname,
            "firstname" => $this->firstname,
            "middlename" => $this->middlename,
            "ext_name" => $this->ext_name,
            "date_of_birth" => $this->date_of_birth,
            "gender" => $this->gender,
            "home_address" => $this->home_address,
			"region" => $this->region,
			"province" => $this->province,
			"city" => $this->city,
			"barangay" => $this->barangay,
            "contact_no" => $this->contact_no,
            "student_status" => $this->student_status,
            "email_address" => $this->email_address,
            "indigenous" => $this->indigenous,
            "mother_tongue" => $this->mother_tongue,
            "house_no" => $this->house_no,
            "zip_code" => $this->zip_code,
			"relationship" => $this->parents()->first()->relationship,
			"gp_firstname" => $this->parents()->first()->first_name,
			"gp_middlename" => $this->parents()->first()->middle_name,
			"gp_lastname" => $this->parents()->first()->last_name,
			"gp_contact_no" => $this->parents()->first()->contact_no,
			"updated_dt" => Carbon::parse($this->update_log)->format('Y-m-d H:i:s'),
			"indigent" => (is_null($this->indigenous))?false:true,
            'previous_level_id' => $recent_level_id,
            'previous_level' => $recent_level_description,
            "next_level_id" => $next_level_id,
            "next_level" => $next_level_description,
            "discounts" => $discounts,
        ];
    }

}
