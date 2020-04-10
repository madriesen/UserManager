<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\Invite;

use App\Invite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/*
 * // an_invite_acceptance_without_arguments_fails()
 *
 * an_invite_acceptance_with_an_non_existing_invite_id_fails()
 *
 * // an_invite_acceptance_with_an_invite_id_from_an_already_declined_invite_fails()
 *
 * // an_invite_acceptance_with_an_invite_id_from_an_already_accepted_invite_fails()
 *
 * // an_invite_acceptance_with_an_invite_id_from_a_not_responded_invite_passes()
 *
 * // an_invite_declinal_without_arguments_fails()
 *
 * // an_invite_declinal_with_an_invite_id_from_an_already_declined_invite_fails()
 *
 * an_invite_declinal_with_an_invite_id_from_an_already_accepted_invite_fails()
 *
 * // an_invite_declinal_with_an_invite_id_from_a_not_responded_invite_passes()
 */

class RespondInviteTest extends TestCase
{
    use RefreshDatabase;

    private $invite_id;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $invite = Invite::create();
        $this->invite_id = $invite->id;

    }

    /** @test */
    public function an_invite_acceptance_without_arguments_fails()
    {
        $this->withoutEvents();
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'accepted_at' => null]);
        $response = $this->postJson(route('acceptInvite'));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['invite_id' => 'Please, enter a valid invite']]]);
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'accepted_at' => null]);
    }

    /** @test */
    public function an_invite_acceptance_with_an_non_existing_invite_id_fails()
    {
        $this->withoutEvents();
        $heighestInviteId = \Invite::getHeighestId();
        $response = $this->postJson(route('acceptInvite'), ['invite_id' => $heighestInviteId + 1]);
        $response->assertJsonStructure(['error' => ['message']]);
    }

    /** @test */
    public function an_invite_acceptance_with_an_invite_id_from_a_not_responded_invite_passes()
    {
        $this->withoutEvents();
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'accepted_at' => null]);
        $response = $this->_acceptInvite();
        $response->assertJsonStructure(['success']);
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'accepted_at' => Date::now()]);
    }

    /** @test */
    public function an_invite_acceptance_with_an_invite_id_from_an_already_declined_invite_fails()
    {
        $this->withoutEvents();
        $this->_declineInvite();
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'declined_at' => Date::now()]);
        $response = $this->_acceptInvite();
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'The invite is already responded']]);
    }

    /** @test */
    public function an_invite_acceptance_with_an_invite_id_from_an_already_accepted_invite_fails()
    {
        $this->withoutEvents();
        $this->_acceptInvite();
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'accepted_at' => Date::now()]);
        $response = $this->_acceptInvite();
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'The invite is already responded']]);

    }

    /** @test */
    public function an_invite_declinal_without_arguments_fails()
    {
        $this->withoutEvents();
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'declined_at' => null]);
        $response = $this->postJson(route('declineInvite'));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['invite_id' => 'Please, enter a valid invite']]]);
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'declined_at' => null]);
    }

    /** @test */
    public function an_invite_declinal_with_an_invite_id_from_a_not_responded_invite_passes()
    {
        $this->withoutEvents();
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'declined_at' => null]);
        $response = $this->_declineInvite();
        $response->assertJsonStructure(['success']);
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'declined_at' => Date::now()]);
    }

    /** @test */
    public function an_invite_declinal_with_an_invite_id_from_an_already_declined_invite_fails()
    {
        $this->withoutEvents();
        $this->_declineInvite();
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'declined_at' => Date::now()]);
        $response = $this->_declineInvite();
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'The invite is already responded']]);
    }

    /** @test */
    public function an_invite_declinal_with_an_invite_id_from_an_already_accepted_invite_fails()
    {
        $this->withoutEvents();
        $this->_acceptInvite();
        $this->assertDatabaseHas('invites', ['id' => $this->invite_id, 'accepted_at' => Date::now()]);
        $response = $this->_declineInvite();
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => 'The invite is already responded']]);
    }


    /**
     * @return TestResponse
     */
    public function _acceptInvite(): TestResponse
    {
        return $this->postJson(route('acceptInvite'), ['invite_id' => $this->invite_id]);
    }

    /**
     * @return TestResponse
     */
    public function _declineInvite(): TestResponse
    {
        return $this->postJson(route('declineInvite'), ['invite_id' => $this->invite_id]);
    }
}
