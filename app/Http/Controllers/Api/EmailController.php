<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Notifications\SendEmail;
use Illuminate\Support\Facades\Notification;

class EmailController extends Controller
{
    /**
     * @group Send->Email
     * 
     * Send email
     *
     * @bodyParam message string required
     * @bodyParam email_address string required
     * 
     */
    public function send(Request $request)
    {
        $message = $request->message;
        $email_address = $request->email_address;

        Notification::route('mail', $email_address)->notify(new SendEmail($message));

        return response()->json(["status" => true]);
    }
}
