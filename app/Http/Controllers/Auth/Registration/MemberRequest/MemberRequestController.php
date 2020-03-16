<?php

namespace App\Http\Controllers\Auth\Registration\MemberRequest;

use App\Events\MemberRequest as MemberRequestEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequestAnswerRequest;
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

        // fire event memberrequest created
        event(new MemberRequestEvent\Created($member_request));

        // send response
        return response()->json(['data' => ['email' => $email, 'member_request' => $member_request]]);
    }

    public function approve(MemberRequestAnswerRequest $request)
    {
        $id = $request->id;

        MemberRequest::find($id)->approvedAt = time();

        return response()->json(['data' => ['member_request' => MemberRequest::find($id)]]);
    }


    public function refuse(MemberRequestAnswerRequest $request)
    {
        $id = $request->id;

        MemberRequest::find($id)->refusedAt = time();

        return response()->json(['data' => ['member_request' => MemberRequest::find($id)]]);
    }

    public function getAll()
    {
        $request_data = [];

        foreach (MemberRequest::all() as $request) {
            array_push($request_data, ['request' => $request, 'email' => $request->email]);
        }

        return (response()->json(['data' => $request_data]));
    }
}
