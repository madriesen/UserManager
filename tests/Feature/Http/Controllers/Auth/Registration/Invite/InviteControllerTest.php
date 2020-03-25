<?php

namespace Tests\Feature\Http\Controllers\Auth\Registration\Invite;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InviteControllerTest extends TestCase
{

    use RefreshDatabase;
    private $member_request;
    private $email;

    function setUp(): void
    {
        parent::setUp();
        $response_data = $this->post(route('memberRequest'), ['email' => 'test@mail.be'])["data"];

        $this->member_request = $response_data["member_request"];
        $this->email = $response_data["email"];

        $this->post(route('approveMemberRequest'), [$response_data["member_request"]["id"]]);
    }


    /** @test */
    function an_invite_can_be_created()
    {
        $response = $this->post('/api/registration/invite/create', ['member_request_id' => $this->member_request['id'], 'email_id' => $this->email['id']]);
        $response->assertStatus(200);

        $response->assertJsonStructure(['data' => ['email', 'invite']]);
    }

    /** @test */
    function an_invite_can_be_accepted()
    {
        $response = $this->post(route('invite'), ['member_request_id' => $this->member_request['id'], 'email_id' => $this->email['id']]);
        $invite = $response["data"]["invite"];

        $response = $this->post(route('acceptInvite'), ['invite_id' => $invite["id"]]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['invite', 'email']]);
        $response->assertJson(['data' => ['email' => $this->email]]);
    }
}
