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
        $get_enrollments = $this->enrollments()->orderByDesc('system_log');
        // $enrollments = $get_enrollments->get();
        $recent_enrollment = $get_enrollments->first();
        $recent_level = $recent_enrollment->level;

        $recent_level_id = $recent_level->id;
        $next_level = GradeLevel::find($recent_level_id+1);
        $next_level_id = $next_level->id;

        $current_sy = $this->currentSy();

        $fees = Fee::where('school_year',$current_sy)->get();
        $fees = $fees->map(function($fee) use ($next_level_id) {
            $item = FeeItem::where([['fee_id',$fee->id],['level',$next_level_id]])->first();
            $fee->amount = $item->amount;
            return $fee;
        });

        $total_fees = collect($fees)->sum('amount');

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
            "next_level_id" => $next_level->id,
            "next_level" => $next_level->description,
            "fees" => $fees,
            "total_fees" => $total_fees,
            "down_payment" => $this->getDownPayment($next_level->id)
        ];
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

}
