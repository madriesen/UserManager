<?php

namespace Tests\Feature\Http\Controllers\Auth\Registration\MemberRequest;

use App\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;

class MemberRequestControllerTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_member_request_can_be_made()
    {
        $response = $this->post(route('memberRequest'), ['email' => 'test@mail.be']);
        $response->assertStatus(200);

        $response = $this->post('/api/memberrequest/create', ['email' => 'test@mail.be']);
        $response->assertStatus(200);
    }

    /** @test */
    public function a_member_request_accepts_one_emailaddress()
    {
        $response = $this->post(route('memberRequest'), ['email' => 'test@mail.be']);
        $response->assertJsonStructure(['data' => ['email', 'member_request']]);
    }

    /** @test */
    public function an_invalid_emailaddress_cannot_pass()
    {
        $this->withoutExceptionHandling();

        try {
            $response = $this->post(route('memberRequest'), ['email' => 'testAtMailPuntBE']);
        } catch (ValidationException $e) {
            $this->assertEquals('This is not a valid emailaddress', $e->validator->errors()->first());
            return;
        }
        $this->fail("The email passed validation when it should have failed.");
    }


    /** @test */
    public function after_a_meber_request_a_member_request_is_added_to_the_database()
    {
        $response = $this->post(route('memberRequest'), ['email' => 'test@mail.be']);
        $this->assertDatabaseHas('member_requests', ['id' => $response["data"]["member_request"]["id"]]);
    }

    /** @test */
    public function after_a_member_request_an_email_is_added_to_the_database()
    {
        $this->post(route('memberRequest'), ['email' => 'test@mail.be']);

        $this->assertDatabaseHas('emails', ['address' => 'test@mail.be']);
    }

    /** @test */
    public function an_email_is_linked_to_a_request()
    {
        $this->post(route('memberRequest'), ['email' => 'test@mail.be']);
        $this->assertTrue(Email::first()->member_request->id == 1);
    }

    /**  @test */
    public function a_member_request_can_be_approved()
    {
        $response = $this->post(route('memberRequest'), ['email' => 'test@mail.be']);

        $response = $this->post(route('approveMemberRequest'), ['id' => $response["data"]["member_request"]["id"]]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['member_request']]);

        $response = $this->post('/api/memberrequest/approve', ['id' => $response["data"]["member_request"]["id"]]);
        $response->assertStatus(200);
    }

    /** @test */
    public function a_member_request_can_be_refused()
    {
        $response = $this->post(route('memberRequest'), ['email' => 'test@mail.be']);

        $response = $this->post(route('refuseMemberRequest'), ['id' => $response["data"]["member_request"]["id"]]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['member_request']]);

        $response = $this->post('/api/memberrequest/refuse', ['id' => $response["data"]["member_request"]["id"]]);
        $response->assertStatus(200);
    }

    /** @test */
    public function all_member_requests_can_be_listed_with_emailaddresses()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('memberRequest'), ['email' => "test{$i}@mail.be"]);
        }

        $response = $this->get(route('getAllMemberRequests'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['0' => ['request' => ['approvedAt', 'refusedAt', 'created_at', 'email']], '1', '2', '3', '4']]);

        for ($i = 0; $i < 5; $i++) {
            $this->assertTrue($response['data']["{$i}"]['request']['email']['address'] === "test{$i}@mail.be");
        }


        $response = $this->get('/api/memberrequest/all');
        $response->assertStatus(200);
    }
}
