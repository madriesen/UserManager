<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\MemberRequest;

use App\Email;
use App\MemberRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Illuminate\Testing\TestResponse;
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
 * // a_member_request_refusal_without_arguments_fails()
 *
 * // a_member_request_refusal_with_an_already_approved_member_request_id_fails()
 *
 * a_member_request_refusal_with_an_already_refused_member_request_id_fails()
 *
 * // a_member_request_refusal_with_a_not_yet_replied_member_request_id_passes()
 */

class RespondMemberRequestTest extends TestCase
{
    use RefreshDatabase;
    private $member_request_id;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com']);
        $this->member_request_id = Email::all()->firstWhere('address', 'test@testing.com')->member_request->id;
    }

    /** @test */
    public function a_member_request_approval_without_arguments_fails()
    {
        $this->withoutEvents();
        $this->assertDatabaseHas('member_requests', ['approved_at' => NULL]);
        $response = $this->postJSON(route('approveMemberRequest'));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['member_request_id' => 'Please, enter a valid member request id']]]);
        $this->assertDatabaseHas('member_requests', ['approved_at' => NULL]);
    }

    /** @test */
    public function a_member_request_approval_with_a_not_yet_replied_member_request_id_passes()
    {
        $this->withoutEvents();
        $this->assertDatabaseHas('member_requests', ['approved_at' => NULL]);
        $response = $this->_approveMemberRequest();
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('member_requests', ['approved_at' => NULL]);
    }


    /** @test */
    public function a_member_request_approval_with_an_already_approved_member_request_id_fails()
    {
        $this->_approveMemberRequest();
        $response = $this->_approveMemberRequest();
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'The request is already responded']]);
        $this->assertDatabaseHas('member_requests', ['approved_at' => Date::now()]);
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
        $response = $this->_refuseMemberRequest();
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('member_requests', ['refused_at' => NULL]);
    }

    /** @test */
    public function a_member_request_refusal_with_an_already_approved_member_request_id_fails()
    {
        $this->_approveMemberRequest();
        $response = $this->_refuseMemberRequest();
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'The request is already responded']]);
    }

    /** @test */
    public function a_member_request_refusal_with_an_already_refused_member_request_id_fails()
    {
        $this->_refuseMemberRequest();
        $response = $this->_refuseMemberRequest();
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'The request is already responded']]);
    }

    /**
     * @return TestResponse
     */
    public function _approveMemberRequest(): TestResponse
    {
        return $this->postJSON(route('approveMemberRequest'), ['member_request_id' => $this->member_request_id]);
    }

    /**
     * @return TestResponse
     */
    public function _refuseMemberRequest(): TestResponse
    {
        return $this->postJSON(route('refuseMemberRequest'), ['member_request_id' => $this->member_request_id]);
    }
}
