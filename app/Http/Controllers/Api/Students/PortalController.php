<?php

namespace App\Http\Controllers\Api\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\User;
use App\Models\Student;
use App\Models\Enrollment;

use App\Http\Resources\Students\Profile;
use App\Http\Resources\Students\EnrollmentsCollection;
use App\Http\Resources\Students\Enrollment as EnrollmentDetail;

class PortalController extends Controller
{

    use Messages, Dumper;

    private $http_code_ok;
    private $http_code_error;

    public function  __construct()
    {
		$this->middleware(['auth:api']);
		
        $this->http_code_ok = 200;
        $this->http_code_error = 500;
    }

    /**
     * @group Students->Portal
     * 
     * Student Profile
     * 
     * @authenticated
     */
    public function profile()
    {
        $user = Auth::guard('api')->user();

        $student_id = $user->student_id;

        $student = Student::find($student_id);

        $data = new Profile($student);

        return $this->jsonSuccessResponse($data, $this->http_code_ok); 
    }

    /**
     * @group Students->Portal
     * 
     * Student Enrollments List
     * 
     * @authenticated
     */
    public function enrollments(Request $request)
    {
        $user = Auth::guard('api')->user();

        $student_id = $user->student_id;

        $student = Student::find($student_id);

        $enrollments = $student->enrollments;
        $results = new EnrollmentsCollection($enrollments);

        return $this->jsonSuccessResponse($results, $this->http_code_ok); 
    }

    /**
     * @group Students->Portal
     * 
     * Enrollment detail
     * 
     * @urlParam id integer required
     * 
     * @authenticated
     */
    public function enrollment($id)
    {
        $enrollment = Enrollment::find($id);

        $data = new EnrollmentDetail($enrollment);

        return $this->jsonSuccessResponse($data, $this->http_code_ok); 
    }

}
