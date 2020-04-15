<?php

namespace Tests\Unit\Http\Controllers\Auth\Profile;

use App\Email;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase;

    private int $account_id;
    private int $profile_id;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com']);
        $this->postJson(route('approveMemberRequest'), ['member_request_id' => Email::all()->firstWhere('address', 'test@testing.com')->member_request->id]);
        $this->postJson(route('acceptInvite'), ['invite_id' => \Email::findByAddress('test@testing.com')->invite->id]);
        $this->account_id = \Email::findByAddress('test@testing.com')->account->id;
        $this->postJson(route('profile'), $this->_initialProfileData());
        $this->profile_id = \Profile::findByName($this->_initialProfileData()['name'])->first()->id;
    }

    /** @test */
    public function a_profile_update_without_arguments_fails()
    {
        $this->assertDatabaseHas('profiles', $this->_initialProfileData());
        $response = $this->postJson(route('updateProfile'));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['profile_id' => 'Please, enter a valid profile']]]);
        $this->assertDatabaseHas('profiles', $this->_initialProfileData());
    }

    /** @test */
    public function a_profile_update_with_a_non_existing_profile_id_fails()
    {
        $this->assertDatabaseHas('profiles', $this->_initialProfileData());
        $response = $this->postJson(route('updateProfile'), ['profile_id' => $this->profile_id + 1]);
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['profile_id' => 'Please, enter an existing profile']]]);
        $this->assertDatabaseMissing('profiles', array_merge($this->_initialProfileData(), ['profile_id' => $this->profile_id + 1]));
        $this->assertDatabaseHas('profiles', $this->_initialProfileData());
    }

    /** @test */
    public function a_profile_update_with_an_existing_profile_id_passes()
    {
        $this->assertDatabaseHas('profiles', $this->_initialProfileData());
        $response = $this->postJson(route('updateProfile'), ['profile_id' => $this->profile_id]);
        $response->assertJsonStructure(['success', 'data']);
        $this->assertDatabaseHas('profiles', $this->_initialProfileData());
    }

    /** @test */
    public function a_profile_update_with_an_existing_profile_id_and_additional_data_passes()
    {
        $this->assertDatabaseHas('profiles', $this->_initialProfileData());
        $response = $this->postJson(route('updateProfile'), array_merge($this->_updateProfileData(), ['profile_id' => $this->profile_id]));
        $response->assertJsonStructure(['success', 'data']);
        $this->assertDatabaseHas('profiles', $this->_updateProfileData());
    }

    /**
     * @return array
     */
    public function _initialProfileData(): array
    {
        return [
            'account_id' => $this->account_id,
            'name' => 'Doe',
            'first_name' => 'John',
            'tel' => '0032471359627',
            'birthday' => Date::create(1998, 11, 05)->toImmutable(),
        ];
    }

    /**
     * @return array
     */
    public function _updateProfileData(): array
    {
        return [
            'name' => 'Test Doe',
            'first_name' => 'Test John',
            'tel' => '00324950423983',
            'birthday' => Date::create(1998, 11, 05)->toImmutable(),
        ];
    }
}
