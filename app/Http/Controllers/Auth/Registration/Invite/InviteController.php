<?php

namespace App\Http\Controllers\Auth\Registration\Invite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Invite\CreateInviteRequest;
use App\Http\Requests\Api\Invite\ResponseInviteRequest;
use Illuminate\Support\Facades\Response;

class InviteController extends Controller
{
    public function __invoke(CreateInviteRequest $request)
    {
        $member_request = \MemberRequest::findById($request->member_request_id);
        if ($this->_MemberRequestIsInvalid($member_request))
            return Response::error('member_request', 'Please, enter a valid member request');

        \Invite::createByMemberRequestId($member_request->id);

        return Response::success();
    }

    public function response(ResponseInviteRequest $request)
    {
        $invite = \Invite::findByToken($request->invite_token);
        if ($this->_InviteIsInvalid($invite))
            return Response::error('invite', 'The invite is already responded');

        $this->_AcceptOrDeclineByResponse($request);

        return Response::success();
    }

    public function getAll()
    {
        $invites = \Invite::all();
        return Response::success($invites);
    }


    // ------------------------- //
    // --  private functions  -- //
    // ------------------------- //

    /**
     * @param $member_request
     * @return bool
     */
    private function _MemberRequestIsInvalid($member_request): bool
    {
        return (!$member_request->responded || $member_request->refused);
    }

    /**
     * @param ResponseInviteRequest $request
     */
    public function _AcceptOrDeclineByResponse(ResponseInviteRequest $request): void
    {
        $response = $request->route()->getAction()['response'];
        if ($response === 'accept')
            \Invite::acceptByToken($request->invite_token);
        elseif ($response === 'decline')
            \Invite::declineByToken($request->invite_token);
    }

    /**
     * @param $invite
     * @return bool
     */
    public function _InviteIsInvalid($invite): bool
    {
        return ($invite->declined) || ($invite->accepted);
    }
}
