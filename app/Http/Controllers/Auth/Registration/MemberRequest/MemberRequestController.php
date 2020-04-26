<?php

namespace App\Http\Controllers\Auth\Registration\MemberRequest;

use App\Exceptions\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\Http\Requests\Api\MemberRequest\ResponseMemberRequest;
use App\MemberRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class MemberRequestController extends Controller
{
    /**
     * @param CreateMemberRequestRequest $request
     * @return JsonResponse
     */
    public function __invoke(CreateMemberRequestRequest $request)
    {
        if ($this->_chkEmailIsInvalid($request))
            return Response::error('email_address', 'The request is already made.');

        \MemberRequest::create($request);

        return Response::success();
    }

    /**
     * @param ResponseMemberRequest $request
     * @return JsonResponse
     */
    public function response(ResponseMemberRequest $request)
    {
        $member_request = \MemberRequest::findByUUID($request->member_request_id);
        if ($this->_chkMemberRequestIsInvalid($member_request))
            return Response::error('member_request', 'The request is already responded');

        $this->_approveOrRefuseAccordingToResponse($request, $member_request);

        return Response::success();
    }

    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        return Response::success(\MemberRequest::all());
    }


    // ------------------------- //
    // --  private functions  -- //
    // ------------------------- //

    /**
     * @param CreateMemberRequestRequest $request
     * @return bool
     */
    private function _chkEmailIsInvalid(CreateMemberRequestRequest $request): bool
    {
        try {
            $email = \Email::findByAddress($request->email_address)->first();
        } catch (ModelNotFoundException $e) {
            return false;
        }
        return (($email->member_request->approved || !$email->member_request->refused));
    }

    /**
     * @param MemberRequest $member_request
     * @return bool
     */
    private function _chkMemberRequestIsInvalid(MemberRequest $member_request): bool
    {
        return (!$member_request->refused && $member_request->approved) || ($member_request->refused && !$member_request->approved);
    }

    /**
     * @param ResponseMemberRequest $request
     * @param MemberRequest $member_request
     */
    private function _approveOrRefuseAccordingToResponse(ResponseMemberRequest $request, MemberRequest $member_request): void
    {
        $response = $request->route()->getAction()['response'];
        if ($response === 'approve') \MemberRequest::approveById($member_request->id, $request);
        elseif ($response === 'refuse') \MemberRequest::refuseById($member_request->id);
    }
}
