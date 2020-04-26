<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\MemberRequest;

use App\Exceptions\ArgumentNotSetException;
use App\Exceptions\InvalidEmailException;
use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class MemberRequestRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
    }

    /** @test */
    public function a_member_request_can_be_created()
    {
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);
        $this->_createMemberRequest(['email_address' => $this->email_address]);
        $this->assertDatabaseHas('member_requests', ['created_at' => Date::now()]);
    }

    /**
     * @param array $data
     * @return string
     */
    private function _createMemberRequest(array $data): string
    {
        return \MemberRequest::create(new CreateMemberRequestRequest($data));
    }

    /** @test */
    public function a_member_request_creation_returns_a_uuid()
    {
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);
        $uuid = $this->_createMemberRequest(['email_address' => $this->email_address]);
        $this->assertFalse($uuid == null);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid]);
    }

    /** @test */
    public function when_a_member_request_is_created_an_email_is_added_to_the_database()
    {
        $this->assertDatabaseMissing('emails', ['address' => $this->email_address]);
        $this->_createMemberRequest(['email_address' => $this->email_address]);
        $this->assertDatabaseHas('emails', ['address' => $this->email_address]);
    }

    /** @test */
    public function a_member_request_without_email_address_fails()
    {
        $this->expectException(ArgumentNotSetException::class);
        $this->_createMemberRequest(['name' => $this->name, 'firstname' => $this->first_name]);
        $this->assertDatabaseMissing('member_requests', ['name' => $this->name, 'firstname' => $this->first_name]);
    }

    /** @test */
    public function a_second_member_request_with_the_same_email_addresses_but_not_approved_or_refused_fails()
    {
        $uuid1 = $this->_createMemberRequest(['email_address' => $this->email_address]);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid1]);

        $this->expectException(InvalidEmailException::class);
        $uuid2 = $this->_createMemberRequest(['email_address' => $this->email_address]);

        $this->assertDatabaseMissing('member_requests', ['uuid' => $uuid2]);
    }

    /** @test */
    public function a_second_member_request_with_the_same_email_addresses_not_approved_but_refused_longer_than_two_weeks_ago_passes()
    {
        $uuid1 = $this->_createMemberRequest(['email_address' => $this->email_address]);
        \MemberRequest::refuseByUUID($uuid1);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid1, 'refused_at' => Date::now()]);


        // 13 days later
        Date::setTestNow(Date::now()->addDays(13));
        $this->expectException(InvalidEmailException::class);
        $uuid2 = $this->_createMemberRequest(['email_address' => $this->email_address]);
        $this->assertDatabaseMissing('member_requests', ['uuid' => $uuid2]);

        // 14 days later
        Date::setTestNow(Date::now()->addDays(1));
        $uuid2 = $this->_createMemberRequest(['email_address' => $this->email_address]);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid2]);
    }

    /** @test */
    public function a_member_request_can_be_approved_by_uuid()
    {
        $this->withoutEvents();
        $uuid = $this->_createMemberRequest(['email_address' => $this->email_address]);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid, 'approved_at' => null]);
        \MemberRequest::approveByUUID($uuid);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid, 'approved_at' => Date::now()]);
    }

    /** @test */
    public function a_member_request_can_be_refused_by_uuid()
    {
        $this->withoutEvents();
        $uuid = $this->_createMemberRequest(['email_address' => $this->email_address]);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid, 'refused_at' => null]);
        \MemberRequest::refuseByUUID($uuid);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid, 'refused_at' => Date::now()]);
    }

    /** @test */
    public function all_member_request_can_be_fetched()
    {
        $this->_createMemberRequest(['email_address' => '1' . $this->email_address]);
        $this->_createMemberRequest(['email_address' => '2' . $this->email_address]);
        $this->_createMemberRequest(['email_address' => '3' . $this->email_address]);

        $member_requests = \MemberRequest::all()->toArray();
        $this->assertArrayHasKey('for_email_address', $member_requests[0], '1' . $this->email_address);
        $this->assertArrayHasKey('for_email_address', $member_requests[1], '2' . $this->email_address);
        $this->assertArrayHasKey('for_email_address', $member_requests[2], '3' . $this->email_address);
    }
}
