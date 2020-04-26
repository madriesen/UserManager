<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\MemberRequest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberRequestRoutesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_member_request_create_route_exists()
    {
        $response = $this->postJson(route('member_request'), ['email_address' => $this->email_address]);
        $response->assertStatus(200);
    }

    /** @test */
    public function a_member_request_approval_route_exists()
    {
        $response = $this->postJson(route('approve_member_request'));
        $response->assertSuccessful();
    }

    /** @test */
    public function a_member_request_approval_must_be_done_by_an_authenticated_user()
    {
        $response = $this->postJson(route('approve_member_request'));
        $response->assertJson(['error' => ['message' => ['request' => 'Unauthenticated.']]]);
    }

    /** @test */
    public function a_member_request_refusal_route_exists()
    {
        $response = $this->postJson(route('refuse_member_request'));
        $response->assertSuccessful();
    }


    /** @test */
    public function a_member_request_refusal_must_be_done_by_an_authenticated_user()
    {
        $response = $this->postJson(route('refuse_member_request'));
        $response->assertJson(['error' => ['message' => ['request' => 'Unauthenticated.']]]);
    }
}
