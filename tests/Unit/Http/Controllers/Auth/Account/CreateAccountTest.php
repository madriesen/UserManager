<?php

namespace Tests\Unit\Http\Controllers\Auth\Account;

use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class CreateAccountTest extends TestCase
{
    use RefreshDatabase;

    private $invite_id;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        \MemberRequest::create(new CreateMemberRequestRequest(['email_address' => 'test@testing.com']));
        \Invite::createByMemberRequestId(\Email::findByAddress('test@testing.com')->first()->member_request->id);
        $this->invite_id = \Email::findByAddress('test@testing.com')->first()->invite->id;
        $this->seed(\DatabaseSeeder::class);
    }

    /** @test */
    public function an_account_can_be_created()
    {
        $this->withoutEvents();
        \Account::createByInviteId($this->invite_id);
        $this->assertDatabaseHas('accounts', ['created_at' => Date::now()]);
    }

    /** @test */
    public function an_account_creation_without_arguments_fails()
    {
        $this->withoutEvents();
        $this->assertDatabaseMissing('accounts', ['created_at' => Date::now()]);
        $response = $this->postJson(route('account'));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite_id' => 'Please, enter a valid invite']]]);
        $this->assertDatabaseMissing('accounts', ['created_at' => Date::now()]);
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
        $this->assertDatabaseMissing('accounts', ['created_at' => Date::now()]);
        $response = $this->postJson(route('account'), ['invite_id' => $this->invite_id + 1]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['invite_id' => 'Please, enter a valid invite']]]);
        $this->assertDatabaseMissing('accounts', ['created_at' => Date::now()]);
    }

    /** @test */
    public function an_account_creation_with_a_not_yet_responded_invite_id_fails()
    {
        $this->withoutEvents();
        $this->assertDatabaseMissing('accounts', ['created_at' => Date::now()]);
        $response = $this->postJson(route('account'), ['invite_id' => $this->invite_id]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => 'The invite is not yet responded']]);
        $this->assertDatabaseMissing('accounts', ['created_at' => Date::now()]);
    }

    /** @test */
    public function an_account_creation_with_a_declined_invite_id_fails()
    {
        $this->withoutEvents();
        \Invite::declineById($this->invite_id);
        $this->assertDatabaseMissing('accounts', ['created_at' => Date::now()]);
        $response = $this->postJson(route('account'), ['invite_id' => $this->invite_id]);
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => 'The invite is declined']]);
    }

    /** @test */
    public function an_account_creation_with_an_accepted_invite_id_passes()
    {
        $this->withoutEvents();
        \Invite::acceptById($this->invite_id);
        $this->assertDatabaseMissing('accounts', ['created_at' => Date::now()]);
        $response = $this->postJson(route('account'), ['invite_id' => $this->invite_id]);
        $response->assertJsonStructure(['success']);
        $this->assertDatabaseHas('accounts', ['created_at' => Date::now()]);
    }
}
