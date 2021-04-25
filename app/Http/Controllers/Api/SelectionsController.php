<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Dialect;

use App\Traits\Messages;

class SelectionsController extends Controller
{
    use Messages;

    public function dialects()
    {
        $dialects = Dialect::all(['id','name']);

        return $this->jsonSuccessResponse($dialects, 200);   
    }

}
