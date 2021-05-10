<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Dialect;
use App\Models\IndigenousGroup;
use App\Models\Fee;
use App\Models\FeeItem;
use App\Models\GradeLevel;

use App\Traits\Messages;
use App\Traits\CommonHelpers;

class SelectionsController extends Controller
{
    use Messages, CommonHelpers;

    public function dialects()
    {
        $dialects = Dialect::all(['id','name']);

        return $this->jsonSuccessResponse($dialects, 200); 
    }

    public function indigenousGroups()
    {
        $groups = IndigenousGroup::all(['id','name']);

        return $this->jsonSuccessResponse($groups, 200);   
    }
    
    public function levels()
    {   
        $levels = GradeLevel::all(['id','description']);

        return $this->jsonSuccessResponse($levels, 200);
    }

    public function feesByLevel($level_id)
    {

        $current_sy = $this->currentSy();

        $fees = Fee::where('school_year',$current_sy)->get();
        $tuition_fee = 0;

        foreach ($fees as $i => $fee) {
            $item = FeeItem::where([['fee_id',$fee->id],['level',$level_id]])->first();
            $fees[$i]['amount'] = $item->amount;
            $fees[$i]['fee_item_id'] = $item->id;
            $fees[$i]['no'] = $i+1;
            if ($fee['category'] == 'Tuition Fees') $tuition_fee = $item->amount;
        }

        $total_fees = collect($fees)->sum('amount');

        $down_payment = $this->getDownPayment($level_id);

        $data = [
            'level_description' => $this->levelDescription($level_id),
            'fees' => $fees,
            'total' => $total_fees,
            'down_payment' => $down_payment,
            'tuition_fee' => $tuition_fee,
        ];

        return $this->jsonSuccessResponse($data, 200);
    }

    public function questionnaires()
    {
        $questionnaires = config('constants.questionnaires');

        return $this->jsonSuccessResponse($questionnaires, 200);
    }

}
