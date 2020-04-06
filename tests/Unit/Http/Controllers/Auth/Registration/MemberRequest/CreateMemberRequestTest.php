<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\MemberRequest;

use App\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class CreateMemberRequestTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function a_member_request_without_arguments_fails()
    {
        $response = $this->postJson(route('memberRequest'));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'Please, enter an email address']]], true);
        $this->assertDatabaseMissing('member_requests', ['id' => 1]);

    }

    /** @test */
    public function a_member_request_without_email_address_fails()
    {
        $response = $this->postJson(route('memberRequest'), ['name' => 'Driesen', 'firstname' => 'Martijn']);
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'Please, enter an email address']]], true);
        $this->assertDatabaseMissing('member_requests', ['id' => 1]);

    }

    /** @test */
    public function a_member_request_with_an_invalid_email_address_fails()
    {
        $response = $this->postJson(route('memberRequest'), ['email_address' => 'test']);
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'Please, enter a valid email address']]], true);
        $this->assertDatabaseMissing('member_requests', ['id' => 1]);

    }

    /** @test */
    public function a_member_request_with_one_email_address_passes()
    {
        $this->assertDatabaseMissing('member_requests', ['id' => 1]);
        $response = $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com']);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('member_requests', ['id' => 1]);
        $this->assertDatabaseHas('emails', ['address' => 'test@testing.com']);
    }

    /** @test */
    public function a_second_member_request_with_the_same_email_addresses_but_not_approved_or_refused_fails()
    {
        $this->assertDatabaseMissing('member_requests', ['id' => 1]);
        $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com']);
        $this->assertDatabaseHas('member_requests', ['id' => 1]);

        $response = $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com']);
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'The request is already made.']]);
        $this->assertDatabaseMissing('member_requests', ['id' => 2]);
    }

    /** @test */
    public function a_member_request_with_an_email_address_but_without_a_name_passes()
    {
        $this->assertDatabaseMissing('member_requests', ['first_name' => 'martijn']);
        $response = $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com', 'first_name' => 'martijn']);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('member_requests', ['first_name' => 'martijn']);
    }

    /** @test */
    public function a_member_request_with_an_email_address_but_without_a_first_name_passes()
    {
        $this->assertDatabaseMissing('member_requests', ['name' => 'driesen']);
        $response = $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com', 'name' => 'driesen']);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('member_requests', ['name' => 'driesen']);
    }

    /** @test */
    public function a_member_request_with_an_email_address_and_a_first_name_and_a_name_passes()
    {
        $this->assertDatabaseMissing('member_requests', ['name' => 'driesen', 'first_name' => 'martijn']);
        $response = $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com', 'name' => 'driesen', 'first_name' => 'martijn']);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('member_requests', ['name' => 'driesen', 'first_name' => 'martijn']);
    }

    /** @test */
    public function a_second_member_request_with_the_same_email_addresses_not_approved_but_refused_passes()
    {
        $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com', 'name' => 'driesen', 'first_name' => 'martijn']);
        $this->postJson(route('refuseMemberRequest'), ['member_request_id' => Email::all()->firstWhere('address', 'test@testing.com')->member_request->id]);
        $response = $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com', 'name' => 'driesen', 'first_name' => 'martijn']);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);

    }
}