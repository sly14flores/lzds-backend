<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Rules\CurrentPassword;

use Laravel\Passport\Token;

use App\Traits\Messages;
use App\Traits\Dumper;

use App\Models\User;

class DefaultPasswords extends Controller
{

    use Messages, Dumper;

    private $http_code_ok;
    private $http_code_error;

    public function __construct()
    {
        $this->middleware('auth:api');

        $this->http_code_ok = 200;
        $this->http_code_error = 500;
    }

    /**
     * @group Default->Passwords
     * 
     * Student Default Password
     * Change default password
     * 
     * @bodyParam currentPassword string required
     * @bodyParam newPassword string required
     * @bodyParam confirmNewPassword string required
     * 
     * @authenticated
     */
    public function Student(Request $request)
    {
        $rules = [
            'currentPassword' => ['required', new CurrentPassword],
            'newPassword' => ['required', 'string', 'min:8'],
            'confirmNewPassword' => ['required', 'string', 'min:8', 'same:newPassword'],
        ];

		$messages = [
			'currentPassword.required' => 'Current password is required',
			'newPassword.required' => 'New password is required',
			'newPassword.min' => 'Password must at least be 8 characters',
			'confirmNewPassword.required' => 'Please confirm password',
			'confirmNewPassword.same' => 'Password confirmation does not match'
		];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->jsonErrorDataValidation($validator->errors());
        }       

        /** Get validated data */
        $data = $validator->valid();

        $password = Hash::make($data['newPassword']);

        $user = User::find(Auth::id());

        $user->password = $password;

        $user->save();

        Token::where('user_id', Auth::id())->delete();        

        return $this->jsonSuccessResponse(null, 200, 'Password updated successfully. You will be logged out after clicking ok for security purposes.');
    }

}
