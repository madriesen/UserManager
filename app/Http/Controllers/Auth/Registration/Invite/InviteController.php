<?php

namespace App\Http\Controllers\Auth\Registration\Invite;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Invite\InviteRequest;
use App\Http\Requests\ResponseInviteRequest;
use App\Invite;
use App\MemberRequest;
use App\Repositories\Interfaces\InviteRepositoryInterface;
use App\Repositories\MemberRequestRepository;
use Illuminate\Support\Facades\Response;

class InviteController extends Controller
{

    private InviteRepositoryInterface $invite_repository;

    public function __construct(InviteRepositoryInterface $invite_repository)
    {
        $this->invite_repository = $invite_repository;
    }

    public function __invoke(InviteRequest $request)
    {
        $member_request = MemberRequest::find($request->member_request_id);
        if ($this->_MemberRequestIsInvalid($member_request))
            return Response::error('Please, enter an existing member request.');

        $this->invite_repository->createByMemberRequestId($member_request->id);

        return Response::success();
    }

    public function response(ResponseInviteRequest $request)
    {
        $invite = Invite::find($request->invite_id);
        if ($this->_InviteIsInvalid($invite))
            return Response::error('The invite is already responded');

        $this->_AcceptOrDeclineByResponse($request);

        return Response::success();
    }

    public function getAll()
    {
        $invites = $this->invite_repository->all();
        return Response::success($invites);
    }

//
//    public function getAll()
//    {
//        $request_data = [];
//
//        foreach (Invite::all() as $request) array_push($request_data, $this->_addInviteAndEmail($request));
//
//        return (response()->json(['data' => $request_data]));
//    }


    // ------------------------- //
    // --  private functions  -- //
    // ------------------------- //

    /**
     * @param $member_request
     * @return bool
     */
    private function _MemberRequestIsInvalid($member_request): bool
    {
        return (empty($member_request) || !$member_request->responded || $member_request->refused);
    }

    /**
     * @param ResponseInviteRequest $request
     */
    public function _AcceptOrDeclineByResponse(ResponseInviteRequest $request): void
    {
        $response = $request->route()->getAction()['response'];
        if ($response === 'accept')
            $this->invite_repository->acceptById($request->invite_id);
        elseif ($response === 'decline')
            $this->invite_repository->declineById($request->invite_id);
    }

    /**
     * @param $invite
     * @return bool
     */
    public function _InviteIsInvalid($invite): bool
    {
        return empty($invite) || ($invite->declined) || ($invite->accepted);
    }
}
