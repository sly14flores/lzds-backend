<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Enrollment;
use App\Models\Questionnaire;
use App\Http\Resources\EnrollmentResource;
use App\Http\Resources\EnrollmentOnlineResource;

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
            'down_payment' => 'integer',
            'questionnaires' => 'array',
            // 'enrollment_school_year',
            // 'enrollment_date',
            // 'registered_online',
            // 'enrollee_rn',
            // 'enrollment_uiid',
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

        try {

            DB::beginTransaction();

            $enroll = new Enrollment;
            $enroll->fill($data);
            $enroll->save();

            /**
             * Questionnaires
             */
            $questionnaire = new Questionnaire();
            $questionnaire->fill([
                'answers' => (isset($data['questionnaires']))?$data['questionnaires']:config('contants.questionnaires'),
            ]);
            $enroll->questionnaire()->save($questionnaire);

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

}
