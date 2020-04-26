<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\MemberRequest;

use App\Exceptions\EmailAlreadyExists;
use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class MemberRequestControllerTest extends TestCase
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
        $this->_createMemberRequest();
        $this->assertDatabaseHas('member_requests', ['created_at' => Date::now()]);
    }

    /** @test */
    public function a_member_request_creation_returns_a_uuid()
    {
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);
        $uuid = $this->_createMemberRequest();
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now(), 'uuid' => null]);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid]);

    }

    /** @test */
    public function when_a_member_request_is_created_an_email_is_added_to_the_database()
    {
        $this->assertDatabaseMissing('emails', ['address' => 'test@testing.com']);
        $this->_createMemberRequest();
        $this->assertDatabaseHas('emails', ['address' => 'test@testing.com']);
    }

    /** @test */
    public function a_member_request_create_route_exists()
    {
        $response = $this->postJson(route('member_request'), ['email_address' => 'test@testing.com']);
        $response->assertStatus(200);
    }

    /** @test */
    public function a_member_request_without_email_address_fails()
    {
        $response = $this->postJson(route('member_request'), ['name' => 'driesen', 'firstname' => 'martijn']);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'Please, enter an email address']]], true);
        $this->assertDatabaseMissing('member_requests', ['name' => 'driesen', 'firstname' => 'martijn']);
    }

    /** @test */
    public function a_second_member_request_with_the_same_email_addresses_but_not_approved_or_refused_fails()
    {
        $uuid1 = $this->_createMemberRequest();
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid1]);

        $this->expectException(EmailAlreadyExists::class);
        $uuid2 = $this->_createMemberRequest();

        $this->assertDatabaseMissing('member_requests', ['uuid' => $uuid2]);
    }


    /** @test */
    public function a_second_member_request_with_the_same_email_addresses_not_approved_but_refused_longer_than_two_weeks_ago_passes()
    {
        $uuid1 = $this->_createMemberRequest();
        \MemberRequest::refuseByUUID($uuid1);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid1, 'refused_at' => Date::now()]);


        // 13 days later
        Date::setTestNow(Date::now()->addDays(13));
        $this->expectException(EmailAlreadyExists::class);
        $uuid2 = $this->_createMemberRequest();
        $this->assertDatabaseMissing('member_requests', ['uuid' => $uuid2]);

        // 14 days later
        Date::setTestNow(Date::now()->addDays(1));
        $uuid2 = $this->_createMemberRequest();
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid2]);
    }


    /** @test */
    public function a_member_request_can_be_approved_by_uuid()
    {
        $this->withoutEvents();
        $uuid = $this->_createMemberRequest();
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid, 'approved_at' => null]);
        \MemberRequest::approveByUUID($uuid);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid, 'approved_at' => Date::now()]);
    }

    /** @test */
    public function a_member_request_approval_route_exists()
    {
        $response = $this->postJson(route('approve_member_request'));
        $response->assertSuccessful();
    }

    /** @test */
    public function a_member_request_approval_needs_to_be_done_by_an_authenticated_user()
    {
        $response = $this->postJson(route('approve_member_request'));
        $response->assertJson(['error' => ['message' => ['request' => 'Unauthenticated.']]]);
    }

    /** @test */
    public function a_member_request_can_be_refused_by_uuid()
    {
        $this->withoutEvents();
        $uuid = $this->_createMemberRequest();
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid, 'refused_at' => null]);
        \MemberRequest::refuseByUUID($uuid);
        $this->assertDatabaseHas('member_requests', ['uuid' => $uuid, 'refused_at' => Date::now()]);
    }

    /** @test */
    public function a_member_request_refusal_route_exists()
    {
        $response = $this->postJson(route('refuse_member_request'));
        $response->assertSuccessful();
    }

    /** @test */
    public function a_member_request_refusal_needs_to_be_done_by_an_authenticated_user()
    {
        $response = $this->postJson(route('refuse_member_request'));
        $response->assertJson(['error' => ['message' => ['request' => 'Unauthenticated.']]]);
    }


    /**
     * @return string
     */
    private function _createMemberRequest(): string
    {
        return \MemberRequest::create(new CreateMemberRequestRequest(['email_address' => 'test@testing.com']));
    }
}
