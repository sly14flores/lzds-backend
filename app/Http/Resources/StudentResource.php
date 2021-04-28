<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

class StudentResource extends JsonResource
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
        ];
    }
}
