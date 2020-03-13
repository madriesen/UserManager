<?php

namespace App\Http\Controllers\Auth\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequestApprovelRequest;
use App\Http\Requests\memberRequestRequest;
use App\MemberRequest;
use Symfony\Component\HttpFoundation\Request;

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

    public function approve(MemberRequestApprovelRequest $request)
    {
        $id = $request->id;


        try {
            MemberRequest::find($id)->approvedAt = time();
        } catch (Error $e) {
            return response()->json(['error' => ['message' => 'could not update approve time']]);
        }
        return response()->json(['data' => ['member_request' => MemberRequest::find($id)]]);
    }


    public function deny(MemberRequestApprovelRequest $request)
    {
        $id = $request->id;

        try {
            MemberRequest::find($id)->deniedAt = time();
        } catch (Error $e) {
            return response()->json(['error' => ['message' => 'could not update approve time']]);
        }
        return response()->json(['data' => ['member_request' => MemberRequest::find($id)]]);
    }
}
