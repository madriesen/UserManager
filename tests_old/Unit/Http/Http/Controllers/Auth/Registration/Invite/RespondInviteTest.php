<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\Invite;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RespondInviteTest extends TestCase
{
    use RefreshDatabase;

    private string $invite_token;
    private string $token;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $this->seed(\DatabaseSeeder::class);
        $response = $this->postJson(route('login'), ['email_address' => 'admin@test.be', 'password' => 'test1234']);
        $this->token = $response['data']['token'];

        $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com']);
        $this->withHeaders($this->_headers());
        $this->postJson(route('approveMemberRequest'), ['member_request_id' => \Email::findByAddress('test@testing.com')->member_request->id]);

        $invite = \Email::findByAddress('test@testing.com')->invite;
        $this->invite_token = $invite->token;

        $this->withoutEvents();


    }

    /** @test */
    public function an_invite_acceptance_without_arguments_fails()
    {
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'accepted_at' => null]);
        $response = $this->postJson(route('acceptInvite'));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite_token' => 'Please, enter a valid invite']]]);
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'accepted_at' => null]);
    }

    /** @test */
    public function an_invite_acceptance_with_an_non_existing_invite_token_fails()
    {
        $heighestInviteId = \Invite::getHighestId();
        $response = $this->postJson(route('acceptInvite'), ['invite_token' => Str::random(14)]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite_token' => 'Please, enter an existing invite']]]);
    }

    /** @test */
    public function an_invite_acceptance_with_an_invite_token_from_a_not_responded_invite_passes()
    {
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'accepted_at' => null]);
        $response = $this->_acceptInvite();
        $response->assertJsonStructure(['success']);
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'accepted_at' => Date::now()]);
    }

    /** @test */
    public function an_invite_acceptance_with_an_invite_token_from_an_already_declined_invite_fails()
    {
        $this->_declineInvite();
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'declined_at' => Date::now()]);
        $response = $this->_acceptInvite();
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite' => 'The invite is already responded']]]);
    }

    /** @test */
    public function an_invite_acceptance_with_an_invite_token_from_an_already_accepted_invite_fails()
    {
        $this->_acceptInvite();
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'accepted_at' => Date::now()]);
        $response = $this->_acceptInvite();
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite' => 'The invite is already responded']]]);

    }

    /** @test */
    public function an_invite_declinal_without_arguments_fails()
    {
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'declined_at' => null]);
        $response = $this->postJson(route('declineInvite'));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite_token' => 'Please, enter a valid invite']]]);
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'declined_at' => null]);
    }

    /** @test */
    public function an_invite_declinal_with_an_invite_token_from_a_not_responded_invite_passes()
    {
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'declined_at' => null]);
        $response = $this->_declineInvite();
        $response->assertJsonStructure(['success']);
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'declined_at' => Date::now()]);
    }

    /** @test */
    public function an_invite_declinal_with_an_invite_token_from_an_already_declined_invite_fails()
    {
        $this->_declineInvite();
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'declined_at' => Date::now()]);
        $response = $this->_declineInvite();
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite' => 'The invite is already responded']]]);
    }

    /** @test */
    public function an_invite_declinal_with_an_invite_token_from_an_already_accepted_invite_fails()
    {
        $this->_acceptInvite();
        $this->assertDatabaseHas('invites', ['token' => $this->invite_token, 'accepted_at' => Date::now()]);
        $response = $this->_declineInvite();
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite' => 'The invite is already responded']]]);
    }


    /**
     * @return TestResponse
     */
    public function _acceptInvite(): TestResponse
    {
        return $this->postJson(route('acceptInvite'), ['invite_token' => $this->invite_token]);
    }

    /**
     * @return TestResponse
     */
    public function _declineInvite(): TestResponse
    {
        return $this->postJson(route('declineInvite'), ['invite_token' => $this->invite_token]);
    }

    /**
     * @return string[]
     */
    private function _headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ];
    }
}
