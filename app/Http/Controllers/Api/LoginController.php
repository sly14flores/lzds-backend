<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;

use App\Traits\Messages;

use App\Http\Resources\StudentLoginResource;
use App\Http\Resources\StaffLoginResource;

class LoginController extends Controller
{
    use Messages;

    public function __construct()
    {
        $this->middleware('auth:api')->only(['logout']);
    }

    /**
     * @group Authentications
     * 
     * Student Login
     * 
     * Login using lrn and password
     * 
     * @bodyParam lrn string required
     * @bodyParam password string required
     */
    public function student(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lrn' => 'required|string',
            'password' => 'required|string'
        ],[
            'lrn.required' => 'LRN is required',
            'password.required' => 'Password is required'
        ]);

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }
        
        $login = $validator->valid();

        if (!Auth::attempt($login)) {
            return $this->jsonErrorInvalidCredentials();
        }

        $token = Auth::user()->createToken('authToken');
        $user = User::find(Auth::id());
        $student_id = $user->student_id;

        $student = Student::find($student_id);
        $student->token = $token->accessToken;

        $data = new StudentLoginResource($student);

        return $this->jsonSuccessResponse($data, 200);
    }

    /**
     * @group Authentications
     * 
     * Staff Login
     * 
     * Login using email and password
     * 
     * @bodyParam email string required
     * @bodyParam password string required
     */
    public function staff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string'
        ],[
            'email.required' => 'Email is required',
            'password.required' => 'Password is required'
        ]);

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }
        
        $login = $validator->valid();

        if (!Auth::attempt($login)) {
            return $this->jsonErrorInvalidCredentials();
        }

        $token = Auth::user()->createToken('authToken');
        $user = User::find(Auth::id());
        $staff_id = $user->staff_id;

        $staff = Staff::find($staff_id);
        $staff->token = $token->accessToken;

        $data = new StaffLoginResource($staff);

        return $this->jsonSuccessResponse($data, 200);
    }

}
