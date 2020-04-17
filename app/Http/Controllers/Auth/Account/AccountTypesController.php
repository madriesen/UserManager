<?php

namespace App\Http\Controllers\Auth\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Account\AccountType\CreateAccountTypeRequest;
use App\Http\Requests\Api\Account\AccountType\UpdateAccountTypeRequest;
use Illuminate\Support\Facades\Response;

class AccountTypesController extends Controller
{
    public function __invoke(CreateAccountTypeRequest $request)
    {
        \AccountType::create($request->title, $request->description);
        return Response::success();
    }

    public function update(UpdateAccountTypeRequest $request)
    {
        \AccountType::updateById($request->account_type_id, ['title' => $request->title, 'description' => $request->description]);
        return Response::success();

    }
}
