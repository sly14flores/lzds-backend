<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\SchoolYear;
use App\Models\GradeLevel;
use App\Models\Fee;
use App\Models\FeeItem;

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
        $recent_level = $recent_enrollment->level;

        $recent_level_id = $recent_level->id;
        $next_level = GradeLevel::find($recent_level_id+1);
        $next_level_id = $next_level->id;

        // $current_sy = $this->currentSy();

        // $fees = Fee::where('school_year',$current_sy)->get();
        // $fees = $fees->map(function($fee) use ($next_level_id) {
        //     $item = FeeItem::where([['fee_id',$fee->id],['level',$next_level_id]])->first();
        //     $fee->amount = $item->amount;
        //     return $fee;
        // });

        // $total_fees = collect($fees)->sum('amount');

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
            "contact_no" => $this->contact_no,
            "student_status" => $this->student_status,
            "email_address" => $this->email_address,
            "indigenous" => $this->indigenous,
            "mother_tongue" => $this->mother_tongue,
            // "enrollments" => $enrollments,
            // "recent_enrollment" => $recent_enrollment,
            // "recent_level" => $recent_level,
            'previous_level_id' => $recent_level->id,
            'previous_level' => $recent_level->description,
            "next_level_id" => $next_level->id,
            "next_level" => $next_level->description,
            // "fees" => $fees,
            // "total_fees" => $total_fees,
            // "down_payment" => $this->getDownPayment($next_level->id)
        ];
    }

}
