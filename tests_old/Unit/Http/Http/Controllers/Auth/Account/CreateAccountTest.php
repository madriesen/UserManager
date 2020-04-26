<?php

namespace Tests\Unit\Http\Controllers\Auth\Account;

use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\Invite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class CreateAccountTest extends TestCase
{
    use RefreshDatabase;

    private Invite $invite;
    private string $token;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $this->seed(\DatabaseSeeder::class);
        $response = $this->postJson(route('login'), ['email_address' => 'admin@test.be', 'password' => 'test1234']);
        $this->token = $response['data']['token'];

        $this->withHeaders($this->_headers());

        \MemberRequest::create(new CreateMemberRequestRequest(['email_address' => 'test@testing.com']));
        \Invite::createByMemberRequestId(\Email::findByAddress('test@testing.com')->member_request->id);
        $this->invite = \Email::findByAddress('test@testing.com')->invite;
    }

    /** @test */
    public function an_account_can_be_created()
    {
        $this->withoutEvents();
        \Account::createByInviteId($this->invite->id);
        $this->assertDatabaseHas('accounts', ['created_at' => Date::now()]);
    }

    /** @test */
    public function an_account_creation_without_arguments_fails()
    {
        $this->withoutEvents();
        $response = $this->postJson(route('account'));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite_id' => 'Please, enter a valid invite']]]);
    }

    /** @test */
    public function an_account_creation_with_a_not_numerical_argument_fails()
    {
        $this->withoutEvents();
        $response = $this->postJson(route('account'), ['invite_id' => "test"]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite_id' => 'Please, enter a valid invite']]]);
    }

    /** @test */
    public function an_account_creation_with_an_non_existing_invite_id_fails()
    {
        $this->withoutEvents();
        $account_id = \Account::getHighestId();
        $response = $this->postJson(route('account'), ['invite_id' => $this->invite->id + 1]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite_id' => 'Please, enter a valid invite']]]);
        $this->assertDatabaseMissing('accounts', ['created_at' => Date::now(), 'id' => $account_id + 1]);
    }

    /** @test */
    public function an_account_creation_with_a_not_yet_responded_invite_id_fails()
    {
        $this->withoutEvents();
        $response = $this->postJson(route('account'), ['invite_id' => $this->invite->id]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite' => 'The invite is not yet responded']]]);
    }

    /** @test */
    public function an_account_creation_with_a_declined_invite_id_fails()
    {
        $this->withoutEvents();
        $invite = \Invite::findById($this->invite->id);
        \Invite::declineByToken($invite->token);
        $response = $this->postJson(route('account'), ['invite_id' => $this->invite->id]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite' => 'The invite is declined']]]);
    }

    /** @test */
    public function an_account_creation_with_an_accepted_invite_id_passes()
    {
        $this->withoutEvents();
        $invite = \Invite::findById($this->invite->id);
        \Invite::acceptByToken($this->invite->token);
        $response = $this->postJson(route('account'), ['invite_id' => $this->invite->id]);
        $response->assertJsonStructure(['success']);
        $this->assertDatabaseHas('accounts', ['created_at' => Date::now()]);
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
