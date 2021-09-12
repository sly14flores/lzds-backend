<?php

namespace App\Http\Resources\Students;

use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
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
            'id' => $this->id,
            'lrn' => $this->lrn,
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'ext_name' => $this->ext_name,
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth,
            'gender' => $this->gender,
            'home_address' => $this->home_address,
            'contact_no' => $this->contact_no,
            'email_address' => $this->email_address,
            'student_status' => $this->student_status,
            'mother_tongue' => $this->mother_tongue,
            'religion' => $this->religion,
            'ethnicity' => $this->ethnicity,
            'parents' => []
        ];
    }
}
