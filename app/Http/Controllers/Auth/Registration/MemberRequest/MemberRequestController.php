<?php

namespace App\Http\Controllers\Auth\Registration\MemberRequest;

use App\Email;
use App\Events\MemberRequest as MemberRequestEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\Http\Requests\ResponseMemberRequest;
use App\MemberRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Date;

class MemberRequestController extends Controller
{

    public function __invoke(CreateMemberRequestRequest $request)
    {
        if ($this->_chkEmailExists($request))
            return $this->_errorMessage('The request is already made.');

        $this->_createMemberRequest($request);

        return $this->_successMessage();
    }

    public function response(ResponseMemberRequest $request)
    {
        $member_request = $this->_getMemberRequest($request);
        if ($this->_chkMemberRequest($member_request))
            return $this->_errorMessage('The request is already responded');

        $this->_approveOrRefuseAccordingToRequest($request, $member_request);

        return $this->_successMessage();
    }


    public function getAll()
    {
        return $this->_successMessage(MemberRequest::all()->toArray());
    }


    // private functions

    /**
     * @param CreateMemberRequestRequest $request
     * @return bool
     */
    private function _chkEmailExists(CreateMemberRequestRequest $request): bool
    {
        $email = Email::all()->firstWhere('address', $request->email_address);
        return (
            !empty($email) &&
            !empty($email->member_request)
            && (
            ($this->_chkMemberRequestIsApproved($email) ||
                (
                    ((!($this->_chkMemberRequestIsApproved($email)))) &&
                    ($this->_chkMemberRequestIsRefused($email))
                )
            )
            )
        );
    }

    /**
     * @param CreateMemberRequestRequest $request
     * @return mixed
     */
    private function _createMemberRequest(CreateMemberRequestRequest $request)
    {
        $member_request = MemberRequest::create(['name' => $request->name, 'first_name' => $request->first_name]);
        event(new MemberRequestEvent\Created($member_request, $request));
        return $member_request;

    }

    /**
     * @param $member_request
     * @return bool
     */
    private function _chkMemberRequest($member_request): bool
    {
        return (empty($member_request->refused_at) && !empty($member_request->approved_at));
    }


    /**
     * @param ResponseMemberRequest $request
     * @param $member_request
     */
    private function _approveOrRefuseAccordingToRequest(ResponseMemberRequest $request, $member_request): void
    {
        $response = $request->route()->getAction()['response'];

        if ($response === 'approve') $this->_approve($member_request);
        elseif ($response === 'refuse') $this->_refuse($member_request);
    }

    /**
     * @param $member_request
     */
    private function _approve($member_request): void
    {
        $member_request->approved_at = Date::now()->toImmutable();
        $member_request->save();
    }

    /**
     * @param $member_request
     */
    private function _refuse($member_request): void
    {
        $member_request->refused_at = Date::now()->toImmutable();
        $member_request->save();
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
     * @param array $return_data
     * @return JsonResponse
     */
    private function _successMessage(array $return_data = null): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $return_data]);
    }

    /**
     * @param $email
     * @return bool
     */
    private function _chkMemberRequestIsApproved($email): bool
    {
        return !empty($email->member_request->approved_at);
    }

    /**
     * @param $email
     * @return bool
     */
    private function _chkMemberRequestIsRefused($email): bool
    {
        return empty($email->member_request->refused_at);
    }

    /**
     * @param ResponseMemberRequest $request
     * @return mixed
     */
    private function _getMemberRequest(ResponseMemberRequest $request)
    {
        return MemberRequest::find($request->member_request_id);
    }


}
