<?php

namespace Tests\Unit\Http\Controllers\Auth\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private string $email_address;
    private int $member_request_id;
    private int $invite_id;
    private string $password = 'test123';

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(\DatabaseSeeder::class);
        $this->email_address = 'test@testing.com';
        $this->postJson(route('memberRequest'), ['email_address' => $this->email_address]);
        $this->member_request_id = \MemberRequest::findByEmailAddress($this->email_address)->id;
    }

    /** @test */
    public function a_login_request_without_arguments_fails()
    {
        $response = $this->postJson(route('login'));
        $response->assertJsonStructure(['error' => ['message']]);
    }

    /** @test */
    public function a_login_request_without_an_email_address_fails()
    {
        $response = $this->postJson(route('login'), array_merge($this->_getValidData(), ['email_address' => null]));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'Please, enter a valid email address']]]);
    }

    /** @test */
    public function a_login_request_with_an_invalid_email_address_fails()
    {
        $response = $this->postJson(route('login'), array_merge($this->_getValidData(), ['email_address' => 'test123']));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'Please, enter a valid email address']]]);
    }

    /** @test */
    public function a_login_request_with_a_non_existing_email_address_fails()
    {
        $response = $this->postJson(route('login'), array_merge($this->_getValidData(), ['email_address' => 'test-' . $this->email_address]));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'Please, enter an existing email address']]]);
    }

    /** @test */
    public function a_login_request_with_an_email_address_which_does_not_belong_to_an_account_fails()
    {
        $response = $this->postJson(route('login'), array_merge($this->_getValidData(), ['email_address' => $this->email_address]));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'This email address does not belong to an account']]]);
    }

    /** @test */
    public function a_login_request_with_an_email_address_which_is_not_the_primary_email_address_from_an_account_fails()
    {
        $this->_addEmailToAccount();
        $this->_updatePrivateEmailAddress(null);
        $response = $this->postJson(route('login'), array_merge($this->_getValidData(), ['email_address' => $this->email_address]));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['email_address' => 'This email address does not belong to an account']]]);
    }

    /** @test */
    public function a_login_request_without_a_password_fails()
    {
        $response = $this->postJson(route('login'), array_merge($this->_getValidData(), ['password' => null]));
        $response->assertJsonStructure(['error' => ['message']]);
        $response->assertJson(['error' => ['message' => ['password' => 'Please, enter a valid password']]]);
    }

    /** @test */
    public function a_login_request_with_a_valid_email_and_an_incorrect_password_fails()
    {
        $this->_addEmailToAccount();
        $response = $this->postJson(route('login'), $this->_getValidData());
        $response->assertJsonStructure(['error' => ['message' => ['password']]]);
        $response->assertJson(['error' => ['message' => ['password' => 'Incorrect password']]]);
    }

    /** @test */
    public function a_login_request_with_a_valid_email_and_a_correct_password_passes()
    {
        $this->_addEmailToAccount();
        $this->_setPassword();
        $response = $this->postJson(route('login'), $this->_getValidData());
        $response->assertJsonStructure(['success', 'data' => ['token']]);
    }

    /** @test */
    public function a_user_without_token__check_login_fails()
    {
        $response = $this->get(route('checkLogin'));
        $response->assertJsonStructure(['error' => ['message' => []]]);
        $response->assertJson(['error' => ['message' => ['request' => 'Unauthenticated.']]]);
    }

    /** @test */
    public function a_user_with_token_check_login_passes()
    {
        $this->_addEmailToAccount();
        $this->_setPassword();
        $response = $this->postJson(route('login'), $this->_getValidData());
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $response['data']['token'],
            'Accept' => 'application/json'
        ])->get(route('checkLogin'));
        $response->assertOk();
    }

    /**
     * @return array
     */
    public function _getValidData(): array
    {
        return ['password' => $this->password, 'email_address' => $this->email_address];
    }

    /**
     * @return mixed
     */
    private function _addEmailToAccount()
    {
        $response = $this->postJson(route('login'), ['email_address' => 'admin@test.be', 'password' => 'test1234']);
        $this->token = $response['data']['token'];

        $this->withHeaders($this->_headers())->postJson(route('approveMemberRequest'), ['member_request_id' => $this->member_request_id]);
        $this->invite_id = \Invite::findByEmailAddress($this->email_address)->id;
        $this->postJson(route('acceptInvite'), ['invite_token' => \Invite::findById($this->invite_id)->token]);
    }

    private function _updatePrivateEmailAddress(?string $email_address): void
    {
        $account = \Invite::findById($this->invite_id)->email->account;
        $account->primary_email_id = $email_address;
        $account->save();
    }

    private function _setPassword()
    {
        $account = \Account::findByPrimaryEmailAddress($this->email_address);
        \Account::updatePassword($account->id, $this->password);
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
