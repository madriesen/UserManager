<?php

namespace Tests\Unit\Http\Controllers\Auth\Account;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Tests\TestCase;

class EmailControllerTest extends TestCase
{
    use RefreshDatabase;


    private string $token;
    private int $account_id;

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
        $this->postJson(route('acceptInvite'), ['invite_token' => \Email::findByAddress('test@testing.com')->invite->token]);
        $this->account_id = \Email::findByAddress('test@testing.com')->account->id;
        $this->postJson(route('profile'), $this->_initialProfileData());

        // TODO: login and use token for new email address
    }

    /** @test */
    public function an_email_creation_without_arguments_fails()
    {
        $this->assertTrue(true);
        return true;
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
            'birthday' => '1998/11/05',
        ];
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
