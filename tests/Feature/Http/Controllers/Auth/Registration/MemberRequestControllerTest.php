<?php

namespace Tests\Feature\Http\Controllers\Auth\Registration;

use App\Email;
use App\MemberRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
    public function after_request_a_request_is_added_to_the_database()
    {
        $this->post(route('memberRequest'), ['email' => 'test@mail.be']);
        $this->assertDatabaseHas('member_requests', ['id' => 1]);
    }

    /** @test */
    public function after_request_an_email_is_added_to_the_database()
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
}
