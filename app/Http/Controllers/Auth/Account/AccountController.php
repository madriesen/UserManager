<?php

namespace App\Http\Controllers\Auth\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Account\CreateAccountRequest;
use Illuminate\Support\Facades\Response;

class AccountController extends Controller
{


    public function __invoke(CreateAccountRequest $request)
    {
        $invite = \Invite::findById($request->invite_id);
        if (!$invite->responded)
            return Response::error('The invite is not yet responded');
        if ($invite->declined)
            return Response::error('The invite is declined');

        \Account::createByInviteId($request->invite_id);
        return Response::success();
    }

    public function getAll()
    {
        return \Account::all();
    }
}
