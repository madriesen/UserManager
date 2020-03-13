<?php

namespace App\Http\Controllers\Auth\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\memberRequestRequest;
use App\MemberRequest;

class MemberRequestController extends Controller
{
    public function __invoke(memberRequestRequest $request)
    {
        // create member request
        $member_request = MemberRequest::create();

        // add email to request
        $email = $member_request->email()->create(['address' => $request->email]);
        
        // send response
        return response()->json(['data' => ['email' => $email, 'member_request' => $member_request]]);
    }
}
