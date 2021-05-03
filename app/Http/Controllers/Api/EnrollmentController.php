<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Questionnaire;
use App\Models\StudentsFee;
use App\Models\StudentsDiscount;
use App\Http\Resources\EnrollmentResource;
use App\Http\Resources\EnrollmentOnlineResource;

use App\Notifications\EnrollmentNotification;

use App\Traits\Messages;
use App\Traits\CommonHelpers;

class EnrollmentController extends Controller
{
    use Messages, CommonHelpers;

    private $http_code_ok;
    private $http_code_error;
	
	public function __construct()
	{
		
        $this->http_code_ok = 200;
        $this->http_code_error = 500;

	}	

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (filter_var($id, FILTER_VALIDATE_INT) === false ) {
            return $this->jsonErrorInvalidParameters();
        }

        $enrollment = Enrollment::find($id);      

        if (is_null($enrollment)) {
			return $this->jsonErrorResourceNotFound();
        }

        $data = new EnrollmentResource($enrollment);

        return $this->jsonSuccessResponse($data, 200); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function enrollOnline(Request $request)
    {
        $rules = [
            'lrn' => 'string',
            'student_id' => 'integer',
            'email_address' => ['string','email','max:191'],
            'grade' => 'integer',
            'student_status' => 'string',
            'payment_mode' => 'string',
            'payment_method' => 'string',
            // 'down_payment' => 'integer',
            // 'questionnaires' => 'array',
            'esc_voucher_grantee' => 'boolean',
            // 'discount_amount' => 'float'
            // 'enrollment_school_year',
            // 'enrollment_date',
            // 'registered_online',
            // 'enrollee_rn',
            // 'enrollment_uiid',
            'student_fees' => 'array'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            // return $validator->errors();
            return $this->jsonErrorDataValidation();
        }
        
        /** Get validated data */
        $data = $validator->valid();

        $data['enrollment_school_year'] = $this->currentSy();
        $data['enrollment_date'] = Carbon::now();
        $data['registered_online'] = true;
        $data['enrollment_uiid'] = Str::random(20);
        $data['enrollee_rn'] = $this->referenceNo($data['grade']);
        $data['origin'] = 'online';
        if (is_null($data['student_status'])) $data['student_status'] = "Regular";

        try {

            DB::beginTransaction();

            $check_enrollment = Enrollment::where([['enrollment_school_year',$this->currentSy()],['student_id',$data['student_id']]])->first();

            if (is_null($check_enrollment)) {

                $enroll = new Enrollment;
                $enroll->fill($data);
                $enroll->save();

            } else {

                $data = new EnrollmentOnlineResource($check_enrollment);
                DB::commit();
    
                return $this->jsonSuccessResponse($data, 406, 'You are already enrolled in this school year');

            }

            /**
             * Student Fees
             */
            foreach ($data['student_fees'] as $sf) {
                $student_fee = new StudentsFee;
                $student_fee->fill([
                    'fee_item_id' => $sf['fee_item_id'],
                    'amount' => $sf['amount'],
                ]);
                $enroll->enrollment_fees()->save($student_fee);
            }
            /**
             * Student Discount
             */
            $student_discount = new StudentsDiscount;
            $student_discount->amount = $data['discount_amount'];
            $enroll->student_discount()->save($student_discount);

            /**
             * Update email
             */
            $student = Student::find($data['student_id']);
            $student->email_address = $data['email_address'];
            $student->save();

            /**
             * Email
             */
            $payment_methods = [
                'cash' => 'Cash',
                'bank_deposit' => 'Bank Deposit',
                'gcash' => 'Gcash',
                'paypal' => 'Paypal',
            ];
            $urls = [             
                'cash' => '/payment/cash/',
                'bank_deposit' => '/payment/bank/',
                'gcash' => '/payment/gcash/',
                'paypal' => '/payment/paypal/',
            ];

            $parent = $student->parents()->first();
            $email = [
                'parent' => "{$parent->first_name} {$parent->last_name}",
                'student' => "{$student->firstname} {$student->lastname}",
                'grade' => $enroll->level->description,
                'enrollee_rn' => $enroll->enrollee_rn,
                'payment_method' => $payment_methods[$enroll->payment_method],
                'amount_to_pay' => number_format($enroll->total_amount_to_pay,2),
                'url' => env('FRONTEND_URL').$urls[$enroll->payment_method].$enroll->enrollment_uiid,
            ];

            $student->notify(new EnrollmentNotification($email));

            /**
             * Questionnaires
             */
            // $questionnaire = new Questionnaire();
            // $questionnaire->fill([
            //     'answers' => (isset($data['questionnaires']))?$data['questionnaires']:config('contants.questionnaires'),
            // ]);
            // $enroll->questionnaire()->save($questionnaire);

            $data = new EnrollmentOnlineResource($enroll);
            DB::commit();

            return $this->jsonSuccessResponse($data, 200);

        } catch (\Exception $e) {

            DB::rollBack();
            return $this->jsonFailedResponse(null, $this->http_code_error, $e->getMessage());

        }

    }

    private function referenceNo($level)
    {
        $year = $this->currentSyYear();
        $school_year = $this->currentSy();

        $enrollments = Enrollment::where([['enrollment_school_year',$school_year],['origin','online']])->orderByDesc('enrollee_rn')->first();

        $refno = "LZDS{$year}0001";
        if (!is_null($enrollments)) {

            $last_series = $enrollments->enrollee_rn;
            $series = substr($last_series,strlen($last_series)-4,4);
            $last_incr = intval($series)+1;

            $refno = "LZDS{$year}".str_pad($last_incr,4,"0",STR_PAD_LEFT);

        }

        return $refno;
    }

    public function paymentInfo($uuid) {

        $enrollment = Enrollment::where('enrollment_uiid',$uuid)->first();

        if (is_null($enrollment)) {
			return $this->jsonErrorResourceNotFound();
        }

        /**
         * Email payment intructions
         */

        $data = new EnrollmentOnlineResource($enrollment);

        return $this->jsonSuccessResponse($data, 200);

    }

    public function updateGcash(Request $request, $uuid) {

        $enrollment = Enrollment::where('enrollment_uiid',$uuid)->first();

        if (is_null($enrollment)) {
			return $this->jsonErrorResourceNotFound();
        }

        $rules = [
            'gcash_refno' => 'string'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            // return $validator->errors();
            return $this->jsonErrorDataValidation();
        }
        
        /** Get validated data */
        $data = $validator->valid();
        
        $enrollment->fill($data);
        $enrollment->save();

        return $this->jsonSuccessResponse(null, 200, 'Gcash reference number submitted');

    }

    public function updatePaypal(Request $request, $uuid) {

        $enrollment = Enrollment::where('enrollment_uiid',$uuid)->first();

        if (is_null($enrollment)) {
			return $this->jsonErrorResourceNotFound();
        }

        $rules = [
            'paypal_refno' => 'string'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            // return $validator->errors();
            return $this->jsonErrorDataValidation();
        }
        
        /** Get validated data */
        $data = $validator->valid();        
        
        $enrollment->fill($data);
        $enrollment->save();

        return $this->jsonSuccessResponse(null, 200, 'Paypal reference number submitted');

    }    

}
