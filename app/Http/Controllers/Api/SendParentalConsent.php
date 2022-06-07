<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\ParentalConsent;

use App\Traits\Messages;

class SendParentalConsent extends Controller
{
    use Messages;

    private $http_code_ok;
    private $http_code_error;

	public function __construct()
	{
		
        $this->http_code_ok = 200;
        $this->http_code_error = 500;

	}

    /**
     * @group Send->Email
     * 
     * Send Parental Consent
     * 
     * Email with attached parental consent
     * 
     * @bodyParam email string required
     * 
     */
    public function __invoke(Request $request)
    {
        $email = $request->email;

        Mail::to($email)->send(new ParentalConsent());

        return $this->jsonSuccessResponse(null, 200, $this->http_code_ok);
    }
}