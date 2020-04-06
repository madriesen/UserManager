<?php

namespace App\Http\Controllers\Auth\Registration\Invite;

use App\Email;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResponseInviteRequest;
use App\Http\Requests\InviteRequest;
use App\Events\Invite as InviteEvent;
use App\Invite;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Date;


class InviteController extends Controller
{
    public function __invoke(InviteRequest $request)
    {
        $email = Email::find($request->email_id);

        if (!$email) return $this->_errorMessage('Email not found');

        if ($this->_chkMemberRequestExistsApprovedOrDenied($email)) return $this->_errorMessage('Member Request not found');

        $invite = $this->_createInvite($email);

        // fire event invite created
        event(new InviteEvent\Created($invite));

        return $this->_successMessage($invite);
    }

    public function response(ResponseInviteRequest $request)
    {
        $invite = Invite::find($request->invite_id);

        if ($this->_chkInviteExistsAcceptedOrDeclined($invite)) return $this->_errorMessage('Invite does not exist.');

        // dd($request);
        $response = $request->route()->getAction()['response'];

        if ($response === "accept") $this->_accept($invite);
        elseif ($response === "decline") $this->_decline($invite);


        return $this->_successMessage($invite);
    }

    public function getAll()
    {
        $request_data = [];

        foreach (Invite::all() as $request) array_push($request_data, $this->_addInviteAndEmail($request));

        return (response()->json(['data' => $request_data]));
    }


    // private functions

    private function _chkInviteExistsAcceptedOrDeclined($invite): bool
    {
        return !$invite || $invite->accepted_at || $invite->declined_at;
    }

    private function _chkMemberRequestExistsApprovedOrDenied($email): bool
    {
        return !$email->member_request || !$email->member_request->approved_at || ($email->member_request->denied_at);
    }

    private function _addInviteAndEmail(Invite $invite): array
    {
        return ['invite' => $invite, 'email' => $invite->email];
    }

    private function _errorMessage(string $error_message): JsonResponse
    {
        return response()->json(['error' => ['message' => $error_message]]);
    }

    private function _successMessage($invite): JsonResponse
    {
        return response()->json(['data' => $this->_addInviteAndEmail($invite)]);
    }

    private function _createInvite($email)
    {
        $invite = Invite::create();
        $invite->email()->save($email);
        return $invite;
    }

    private function _accept($invite): void
    {
        $invite->accepted_at = Date::now()->toImmutable();
        $invite->save();
        event(new InviteEvent\Accepted($invite));
    }

    private function _decline($invite): void
    {
        $invite->declined_at = Date::now()->toImmutable();
        $invite->save();
        event(new InviteEvent\Declined($invite));
    }
}
