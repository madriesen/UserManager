<?php

namespace Tests\Unit\Http\Controllers\Auth\Profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class CreateProfileTest extends TestCase
{

    use RefreshDatabase;

    private Int $account_id;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $this->seed(\DatabaseSeeder::class);
        $this->postJson(route('memberRequest'), ['email_address' => 'test@testing.com']);
        $this->postJson(route('approveMemberRequest'), ['member_request_id' => \Email::findByAddress('test@testing.com')->first()->member_request->id]);
        $this->postJson(route('acceptInvite'), ['invite_id' => \Email::findByAddress('test@testing.com')->first()->invite->id]);
        $this->account_id = \Email::findByAddress('test@testing.com')->account->id;
    }

    /** @test */
    public function a_profile_creation_without_arguments_fails()
    {
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
        $response = $this->postJson(route('profile'));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['account_id' => 'Please, enter a valid account']]]);
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
    }

    /** @test */
    public function a_profile_creation_with_a_non_existing_account_id_fails()
    {
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
        $response = $this->postJson(route('profile'), array_merge($this->setValidData(), ['account_id' => $this->account_id + 1]));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['account_id' => 'Please, enter an existing account']]]);
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
    }

    /** @test */
    public function a_profile_creation_with_an_existing_account_id_but_without_other_arguments_fails()
    {
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
        $response = $this->postJson(route('profile'), array_merge($this->setValidData(), ['name' => null, 'first_name' => null]));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => []]]);
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
    }

    /** @test */
    public function a_profile_creation_without_a_first_name_fails()
    {
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
        $response = $this->postJson(route('profile'), array_merge($this->setValidData(), ['first_name' => null]));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['first_name' => 'Please, enter a first name']]]);
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
    }

    /** @test */
    public function a_profile_creation_without_a_name_fails()
    {
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
        $response = $this->postJson(route('profile'), array_merge($this->setValidData(), ['name' => null]));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['name' => 'Please, enter a name']]]);
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
    }

    /** @test */
    public function a_profile_creation_with_a_name_and_a_first_name_but_without_an_account_id_fails()
    {
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
        $response = $this->postJson(route('profile'), array_merge($this->setValidData(), ['account_id' => null]));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['account_id' => 'Please, enter a valid account']]]);
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
    }

    /** @test */
    public function a_profile_creation_with_an_existing_account_id_and_a_name_and_a_first_name_passes()
    {
        $this->assertDatabaseMissing('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
        $response = $this->postJson(route('profile'), $this->setValidData());
        $response->assertJsonStructure(['success', 'data']);
        $this->assertDatabaseHas('profiles', array_merge($this->setValidData(), ['created_at' => Date::now()]));
        $this->assertTrue(\Account::findById($this->account_id)->profile == \Profile::findByName('Doe')->first());
        $this->assertTrue(\Account::findById($this->account_id) == \Profile::findByName('Doe')->first()->account);
    }

    /**
     * @return array
     */
    public function setValidData(): array
    {
        return [
            'account_id' => $this->account_id,
            'name' => 'Doe',
            'first_name' => 'John',
            'tel' => '0032471359627',
            'birthday' => Date::create(1998, 11, 05)->toImmutable(),
        ];
    }
}
