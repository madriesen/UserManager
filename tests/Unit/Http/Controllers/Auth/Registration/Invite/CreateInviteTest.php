<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\Invite;

use App\Email;
use App\Invite;
use App\MemberRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

/*
 * // an_invite_without_arguments_fails()
 *
 * // an_invite_with_a_non_existing_member_request_id_fails()
 *
 * // an_invite_with_a_not_responded_member_request_id_fails()
 *
 * // an_invite_with_a_refused_member_request_id_fails()
 *
 * an_invite_with_an_approved_member_request_id_passes()
 */

class CreateInviteTest extends TestCase
{

    use RefreshDatabase;

    private $email_address;
    private $member_request_id;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $this->email_address = 'test@testing.com';
        $this->postJson(route('memberRequest'), ['email_address' => $this->email_address]);
        $this->member_request_id = Email::firstWhere('address', $this->email_address)->member_request->id;
    }

    /** @test */
    public function an_invite_without_arguments_fails()
    {
        $this->withoutEvents();
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
        $response = $this->postJson(route('invite'));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['member_request_id' => 'Please, enter a valid member request']]]);
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
    }

    /** @test */
    public function an_invite_with_a_non_existing_member_request_id_fails()
    {
        $this->withoutEvents();
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
        $non_existing_member_request_id = MemberRequest::max('id') + 1;
        $response = $this->postJson(route('invite'), ['member_request_id' => $non_existing_member_request_id]);
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'Please, enter an existing member request.']]);
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
    }

    /** @test */
    public function an_invite_with_a_not_responded_member_request_id_fails()
    {
        $this->withoutEvents();
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
        $response = $this->postJson(route('invite'), ['member_request_id' => $this->member_request_id]);
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'Please, enter an existing member request.']]);
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
    }

    /** @test */
    public function an_invite_with_a_refused_member_request_id_fails()
    {
        $this->withoutEvents();
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
        $this->postJson(route('refuseMemberRequest'), ['member_request_id' => $this->member_request_id]);
        $response = $this->postJson(route('invite'), ['member_request_id' => $this->member_request_id]);
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'Please, enter an existing member request.']]);
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
    }

    /** @test */
    public function an_invite_with_an_approved_member_request_id_passes()
    {
        $this->withoutEvents();
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
        $this->postJson(route('approveMemberRequest'), ['member_request_id' => $this->member_request_id]);
        $response = $this->postJson(route('invite'), ['member_request_id' => $this->member_request_id]);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('invites', ['created_at' => Date::now()]);
    }
}
