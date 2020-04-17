<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Authentication\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class AuthenticationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request)
    {
        try {
            $account = \Account::findByPrimaryEmailAddress($request->email_address);
        } catch (ModelNotFoundException $e) {
            return Response::error('email_address', 'This email address does not belong to an account');
        }

        if (!Hash::check($request->password, $account->password)) return Response::error('password', 'Incorrect password');
        $token = $account->createToken('logged-in', ['server:login']);
        return Response::success(['token' => $token->plainTextToken]);
    }
}
