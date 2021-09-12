<?php

namespace App\Http\Resources\Students;

use Illuminate\Http\Resources\Json\JsonResource;

class Enrollment extends JsonResource
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

        $enrollent_fees = $this->enrollment_fees()->with('feeItem')->get();
        $subTotal = $enrollent_fees->pluck('amount')->sum();
        $details = $enrollent_fees->map(function($fee) {
            return [
                'id' => $fee->id,
                'description' => $fee->feeItem->fee->description,
                'amount' => $fee->amount,
            ];
        });

        $voucher = $this->student_voucher->amount ?? 0;
        $discount = $this->student_discount->amount ?? 0;
        
        $total = $subTotal - $discount;

        $fees = [
            'details' => $details,
            'discount' => $discount,
            'voucher' => $voucher,
            'subTotal' => $subTotal,
            'total' => $total,
        ];

        return [
            'id' => $this->id,
            'school_year' => $this->school_year->school_year,
            'school_id' => $this->school_id,
            'grade_level' => $this->level->description,
            'rfid' => $this->rfid,
            'fees' => $fees,
        ];
    }
}
