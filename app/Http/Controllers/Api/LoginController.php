<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Traits\Messages;

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
    public function studentLogin(Request $request)
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

        return Auth::user();
    }

}
