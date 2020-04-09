<?php

namespace App\Http\Controllers\Auth\Registration\MemberRequest;

use App\Http\Controllers\Controller;
use App\Email;
use App\MemberRequest;
use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\Http\Requests\Api\MemberRequest\ResponseMemberRequest;
use App\Repositories\Interfaces\MemberRequestRepositoryInterface;
use Illuminate\Http\JsonResponse;

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
        if ($this->_chkEmailIsValid($request))
            return $this->_errorMessage('The request is already made.');

        $this->member_request_repository->create($request);

        return $this->_successMessage();
    }

    /**
     * @param ResponseMemberRequest $request
     * @return JsonResponse
     */
    public function response(ResponseMemberRequest $request)
    {
        $member_request = $this->member_request_repository->findById($request->member_request_id);
        if ($this->_chkMemberRequestIsValid($member_request))
            return $this->_errorMessage('The request is already responded');

        $this->_approveOrRefuseAccordingToRequest($request, $member_request);

        return $this->_successMessage();
    }

    /**
     * @return JsonResponse
     */
    public function getAll()
    {
        return $this->_successMessage($this->member_request_repository->all());
    }


    // ------------------------- //
    // --  private functions  -- //
    // ------------------------- //

    /**
     * @param CreateMemberRequestRequest $request
     * @return bool
     */
    private function _chkEmailIsValid(CreateMemberRequestRequest $request): bool
    {
        $email = Email::all()->firstWhere('address', $request->email_address);
        return (!empty($email) && ($email->member_request->approved || !$email->member_request->refused));
    }

    /**
     * @param MemberRequest $member_request
     * @return bool
     */
    private function _chkMemberRequestIsValid(MemberRequest $member_request): bool
    {
        return (!$member_request->refused && $member_request->approved);
    }

    /**
     * @param ResponseMemberRequest $request
     * @param MemberRequest $member_request
     */
    private function _approveOrRefuseAccordingToRequest(ResponseMemberRequest $request, MemberRequest $member_request): void
    {
        $response = $request->route()->getAction()['response'];

        if ($response === 'approve') $this->member_request_repository->approveById($member_request->id, $request);
        elseif ($response === 'refuse') $this->member_request_repository->refuseById($member_request->id);
    }

    /**
     * @param string $error_message
     * @return JsonResponse
     */
    private function _errorMessage(string $error_message): JsonResponse
    {
        return response()->json(['error' => ['message' => $error_message]]);
    }

    /**
     * @param object $return_data
     * @return JsonResponse
     */
    private function _successMessage($return_data = null): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $return_data]);
    }
}
