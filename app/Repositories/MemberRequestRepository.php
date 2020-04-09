<?php


namespace App\Repositories;


use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\Events\MemberRequest as MemberRequestEvent;
use App\Http\Requests\Api\MemberRequest\ResponseMemberRequest;
use App\MemberRequest;
use App\Repositories\Interfaces\MemberRequestRepositoryInterface;
use Illuminate\Support\Facades\Date;

class MemberRequestRepository implements MemberRequestRepositoryInterface
{
    /**
     * @param CreateMemberRequestRequest $request
     * @return void
     */
    public function create(CreateMemberRequestRequest $request)
    {
        $member_request = MemberRequest::create();
        $member_request->name = $request->name;
        $member_request->first_name = $request->first_name;
        $member_request->save();

        event(new MemberRequestEvent\Created($member_request, $request));
    }

    /**
     * @param $member_request_id
     * @return mixed
     */
    public function findById(Int $member_request_id)
    {
        return MemberRequest::find($member_request_id);
    }

    /**
     * @param Int $member_request_id
     * @param ResponseMemberRequest $request
     */
    public function approveById(Int $member_request_id, ResponseMemberRequest $request)
    {
        $member_request = $this->findById($member_request_id);
        $member_request->approved_at = Date::now()->toImmutable();
        $member_request->save();

        event(new MemberRequestEvent\Approved($member_request, $request));
    }

    /**
     * @param $member_request_id
     */
    public function refuseById(Int $member_request_id)
    {
        $member_request = $this->findById($member_request_id);
        $member_request->refused_at = Date::now()->toImmutable();
        $member_request->save();
    }

    public function all()
    {
        return MemberRequest::all()->map->format();
    }
}