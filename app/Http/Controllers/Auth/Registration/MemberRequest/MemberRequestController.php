<?php

namespace App\Http\Controllers\Auth\Registration\MemberRequest;

use App\Http\Controllers\Controller;
use App\Email;
use App\MemberRequest;
use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\Http\Requests\Api\MemberRequest\ResponseMemberRequest;
use App\Repositories\Interfaces\MemberRequestRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class MemberRequestController extends Controller
{
    private MemberRequestRepositoryInterface $member_request_repository;

    /**
     * MemberRequestController constructor.
     * @param MemberRequestRepositoryInterface $member_request_repository
     */
    public function __construct(MemberRequestRepositoryInterface $member_request_repository)
    {
        $this->member_request_repository = $member_request_repository;
    }

    /**
     * @param CreateMemberRequestRequest $request
     * @return JsonResponse
     */
    public function __invoke(CreateMemberRequestRequest $request)
    {
        if ($this->_chkEmailIsInvalid($request))
            return Response::error('The request is already made.');

        $this->member_request_repository->create($request);

        return Response::success();
    }

    /**
     * @param ResponseMemberRequest $request
     * @return JsonResponse
     */
    public function response(ResponseMemberRequest $request)
    {
        $member_request = $this->member_request_repository->findById($request->member_request_id);
        if ($this->_chkMemberRequestIsInvalid($member_request))
            return Response::error('The request is already responded');

        $this->_approveOrRefuseAccordingToResponse($request, $member_request);

        return Response::success();
    }

    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        return Response::success($this->member_request_repository->all());
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
        $email = Email::all()->firstWhere('address', $request->email_address);
        return (!empty($email) && ($email->member_request->approved || !$email->member_request->refused));
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

        if ($response === 'approve') $this->member_request_repository->approveById($member_request->id, $request);
        elseif ($response === 'refuse') $this->member_request_repository->refuseById($member_request->id);
    }
}
