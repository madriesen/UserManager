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
        $this->postJson(route('member_request'))
            ->assertSuccessful();
    }

    /** @test */
    public function a_member_request_approval_route_exists()
    {
        $this->postJson(route('approve_member_request'))
            ->assertSuccessful();
    }

    /** @test */
    public function a_member_request_approval_must_be_done_by_an_authenticated_user()
    {
        $this->postJson(route('approve_member_request'))
            ->assertJson(['error' => ['message' => ['request' => 'Unauthenticated.']]]);
    }

    /** @test */
    public function a_member_request_refusal_route_exists()
    {
        $this->postJson(route('refuse_member_request'))
            ->assertSuccessful();
    }


    /** @test */
    public function a_member_request_refusal_must_be_done_by_an_authenticated_user()
    {
        $this->postJson(route('refuse_member_request'))
            ->assertJson(['error' => ['message' => ['request' => 'Unauthenticated.']]]);
    }
}
