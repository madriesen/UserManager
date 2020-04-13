<?php

namespace Tests\Feature\Http\Controllers\Auth\Registration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Date;

class FlowTest extends TestCase
{
    use RefreshDatabase;
    private string $email_address;

    public function setUp(): void
    {
        parent::setUp();
        $this->email_address = 'test@testing.com';
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
    }


    /** @test */
    public function when_a_member_request_is_created_then_an_email_is_created()
    {
        $this->assertDatabaseMissing('emails', ['address' => $this->email_address]);
        $this->postJson(route('memberRequest'), ['email_address' => $this->email_address]);
        $this->assertDatabaseHas('emails', ['address' => $this->email_address]);
    }

    /** @test */
    public function when_a_member_request_is_approved_then_an_invite_is_created()
    {
        $this->postJson(route('memberRequest'), ['email_address' => $this->email_address]);
        $member_request = \Email::findByAddress($this->email_address)->first()->member_request;
        $this->postJson(route('approveMemberRequest'), ['member_request_id' => $member_request->id]);
        $this->assertDatabaseHas('invites', ['created_at' => Date::now()]);
    }

    /** @test */
    public function when_an_invite_is_accepted_an_account_is_created()
    {
        $this->postJson(route('memberRequest'), ['email_address' => $this->email_address]);
        $member_request = \Email::findByAddress($this->email_address)->first()->member_request;
        $this->postJson(route('approveMemberRequest'), ['member_request_id' => $member_request->id]);
        $invite = \Email::findByAddress($this->email_address)->first()->invite;
        $this->postJson(route('acceptInvite'), ['invite_id' => $invite->id]);
        $this->assertDatabaseHas('accounts', ['created_at' => Date::now()]);
        $email = \Email::findByAddress('test@testing.com')->first();
        $this->assertTrue($email->account == \Account::findByPrimaryEmailAddress($email->address));
    }
}