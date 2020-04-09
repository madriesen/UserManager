<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\MemberRequest;

use App\Email;
use App\MemberRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
 * // a_member_request_approval_without_arguments_fails()
 *
 * // a_member_request_approval_with_a_not_yet_replied_member_request_id_passes()
 *
 * // a_member_request_approval_with_an_already_approved_member_request_id_fails()
 *
 * a_member_request_approval_with_an_already_refused_member_request_id_fails()
 *
 * a_member_request_approval_with_a_not_yet_replied_member_request_id_passes()
 *
 * a_member_request_refusal_without_arguments_fails()
 *
 * * a_member_request_refusal_with_an_already_accepted_member_request_id_fails()
 *
 * a_member_request_refusal_with_an_already_refused_member_request_id_fails()
 *
 * a_member_request_refusal_with_a_not_yet_replied_member_request_id_passes()
 */

class RespondMemberRequestTest extends TestCase
{
    use RefreshDatabase;
    private $member_request_id;

    public function setUp(): void
    {
        parent::setUp();
        $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com']);
        $this->member_request_id = Email::all()->firstWhere('address', 'test@testing.com')->member_request->id;
    }

    /** @test */
    public function a_member_request_approval_without_arguments_fails()
    {
        $this->assertDatabaseHas('member_requests', ['approved_at' => NULL]);
        $response = $this->postJSON(route('approveMemberRequest'));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['member_request_id' => 'Please, enter a valid member request id']]]);
        $this->assertDatabaseHas('member_requests', ['approved_at' => NULL]);
    }

    /** @test */
    public function a_member_request_approval_with_a_not_yet_replied_member_request_id_passes()
    {
        $this->assertDatabaseHas('member_requests', ['approved_at' => NULL]);
        $response = $this->postJSON(route('approveMemberRequest'), ['member_request_id' => $this->member_request_id]);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('member_requests', ['approved_at' => NULL]);
    }

    /** @test */
    public function a_member_request_approval_with_an_already_approved_member_request_id_fails()
    {
        $this->postJSON(route('approveMemberRequest'), ['member_request_id' => $this->member_request_id]);
        $approved_at = MemberRequest::find($this->member_request_id)->approved_at;
        $response = $this->postJSON(route('approveMemberRequest'), ['member_request_id' => $this->member_request_id]);
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'The request is already responded']]);
        $this->assertDatabaseHas('member_requests', ['approved_at' => $approved_at]);
    }

    /** @test */
    public function a_member_request_refusal_without_arguments_fails()
    {
        $this->assertDatabaseHas('member_requests', ['refused_at' => NULL]);
        $response = $this->postJSON(route('refuseMemberRequest'));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['member_request_id' => 'Please, enter a valid member request id']]]);
        $this->assertDatabaseHas('member_requests', ['refused_at' => NULL]);

    }

    /** @test */
    public function a_member_request_refusal_with_a_not_yet_replied_member_request_id_passes()
    {
        $this->assertDatabaseHas('member_requests', ['refused_at' => NULL]);
        $response = $this->postJSON(route('refuseMemberRequest'), ['member_request_id' => $this->member_request_id]);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('member_requests', ['refused_at' => NULL]);
    }
}
