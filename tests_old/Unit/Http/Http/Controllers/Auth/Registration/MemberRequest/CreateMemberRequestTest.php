<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\MemberRequest;

use App\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;


class CreateMemberRequestTest extends TestCase
{
    use RefreshDatabase;
    private $email_address;
    private $name;
    private $first_name;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $this->email_address = 'test@testing.com';
        $this->name = 'Doe';
        $this->first_name = 'John';
    }


    /** @test */
    public function a_member_request_without_arguments_fails()
    {
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);
        $response = $this->postJson(route('memberRequest'));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'Please, enter an email address']]], true);
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);

    }

    /** @test */
    public function a_member_request_without_email_address_fails()
    {
        $response = $this->postJson(route('memberRequest'), ['name' => $this->name, 'firstname' => $this->first_name]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'Please, enter an email address']]], true);
        $this->assertDatabaseMissing('member_requests', ['name' => $this->name, 'firstname' => $this->first_name]);

    }

    /** @test */
    public function a_member_request_with_an_invalid_email_address_fails()
    {
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);
        $response = $this->postJson(route('memberRequest'), ['email_address' => 'test']);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'Please, enter a valid email address']]], true);
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);
    }

    /** @test */
    public function a_member_request_with_one_email_address_passes()
    {
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);
        $this->assertDatabaseMissing('emails', ['address' => $this->email_address]);
        $response = $this->postJson(route('memberRequest'), ['email_address' => $this->email_address]);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('member_requests', ['created_at' => Date::now()]);
        $this->assertDatabaseHas('emails', ['address' => $this->email_address]);
    }

    /** @test */
    public function a_second_member_request_with_the_same_email_addresses_but_not_approved_or_refused_fails()
    {
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);
        $this->postJson(route('memberRequest'), ['email_address' => $this->email_address]);
        $this->assertDatabaseHas('member_requests', ['created_at' => Date::now()]);

        Date::setTestNow(Date::create(2020, 4, 7, 10, 45)->toImmutable());

        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);
        $response = $this->postJson(route('memberRequest'), ['email_address' => $this->email_address]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'This email address already exists']]]);
        $this->assertDatabaseMissing('member_requests', ['created_at' => Date::now()]);
    }


    /** @test */
    public function a_member_request_with_an_email_address_but_without_a_name_passes()
    {
        $this->assertDatabaseMissing('member_requests', ['first_name' => $this->first_name]);
        $response = $this->postJson(route('memberRequest'), ['email_address' => $this->email_address, 'first_name' => $this->first_name]);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('member_requests', ['first_name' => $this->first_name]);
    }

    /** @test */
    public function a_member_request_with_an_email_address_but_without_a_first_name_passes()
    {
        $this->assertDatabaseMissing('member_requests', ['name' => $this->name]);
        $response = $this->postJson(route('memberRequest'), ['email_address' => $this->email_address, 'name' => $this->name]);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('member_requests', ['name' => $this->name]);
    }

    /** @test */
    public function a_member_request_with_an_email_address_and_a_first_name_and_a_name_passes()
    {
        $this->assertDatabaseMissing('member_requests', ['name' => $this->name, 'first_name' => $this->first_name]);
        $response = $this->postJson(route('memberRequest'), ['email_address' => $this->email_address, 'name' => $this->name, 'first_name' => $this->first_name]);
        $response->assertJsonStructure(['success']);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('member_requests', ['name' => $this->name, 'first_name' => $this->first_name]);
    }

    /** @test */
    public function a_second_member_request_with_the_same_email_addresses_not_approved_but_refused_fails()
    {

        $this->postJson(route('memberRequest'), ['email_address' => $this->email_address, 'name' => $this->name, 'first_name' => $this->first_name]);
        $this->postJson(route('refuseMemberRequest'), ['member_request_id' => Email::all()->firstWhere('address', $this->email_address)->member_request->id]);
        $response = $this->postJson(route('memberRequest'), ['email_address' => $this->email_address, 'name' => 'Driesy', 'first_name' => $this->first_name]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'This email address already exists']]]);
        $this->assertDatabaseMissing('member_requests', ['name' => 'Driesy']);

    }
}
