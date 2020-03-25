<?php

namespace App\Http\Controllers\Auth\Registration\Invite;

use App\Email;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteRequest;
use App\Http\Requests\OpenInviteRequest;
use App\Invite;
use App\Events\Invite as InviteEvent;


class InviteController extends Controller
{
    public function __invoke(InviteRequest $request)
    {
        $invite = Invite::create();
        $email = Email::findOrFail($request->email_id);

        $invite->email()->save($email);

        // fire event invite created
        event(new InviteEvent\Created($invite));


        return response()->json(['data' => ['invite' => $invite, 'email' => $email]]);
    }

    public function accept(OpenInviteRequest $request)
    {
        $invite = Invite::findOrFail($request->invite_id);
        $invite->openedAt = time();
        $invite->save();

        event(new InviteEvent\Accepted($invite));

        return response()->json(['data' => ['invite' => $invite, 'email' => $invite->email]]);

    }
}
