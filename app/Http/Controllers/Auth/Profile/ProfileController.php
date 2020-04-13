<?php

namespace App\Http\Controllers\Auth\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\CreateProfileRequest;
use App\Http\Requests\Api\Profile\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProfileController extends Controller
{
    public function __invoke(CreateProfileRequest $request)
    {
        \Profile::createByAccountId($request->account_id, $request);

        return Response::success();
    }

    public function update(UpdateProfileRequest $request)
    {
        \Profile::updateById($request);
        return Response::success();
    }
}
