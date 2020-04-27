<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\Invite;

use App\Exceptions\ArgumentNotSetException;
use App\Exceptions\InvalidEmailException;
use App\Http\Requests\Api\Invite\CreateInviteRequest;
use App\Http\Requests\Api\MemberRequest\CreateMemberRequestRequest;
use App\Mail\InviteMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InviteRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private string $member_request_uuid;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $this->member_request_uuid = \MemberRequest::create(new CreateMemberRequestRequest(['email_address' => $this->email_address]));
    }

    /** @test */
    public function an_invite_can_be_created_by_member_request_uuid()
    {
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
        $this->_createInvite(['member_request_uuid' => $this->member_request_uuid]);
        $this->assertDatabaseHas('invites', ['created_at' => Date::now()]);
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function _createInvite(array $data)
    {
        $this->withoutEvents();
        \MemberRequest::approveByUUID($this->member_request_uuid);
        $uuid = \Invite::createByMemberRequestUUID(new CreateInviteRequest($data));
        return $uuid;
    }

    /** @test */
    public function an_invite_creation_returns_a_uuid()
    {
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
        $uuid = $this->_createInvite(['member_request_uuid' => $this->member_request_uuid]);
        $this->assertFalse($uuid == null);
        $this->assertDatabaseHas('invites', ['created_at' => Date::now(), 'uuid' => $uuid]);
    }

    /** @test */
    public function an_invite_without_member_request_uuid_fails()
    {
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
        $this->expectException(ArgumentNotSetException::class);
        $this->_createInvite([]);
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
    }

    /** @test */
    public function an_invite_creation_for_an_already_invited_email_fails()
    {
        $this->_createInvite(['member_request_uuid' => $this->member_request_uuid]);
        Date::setTestNow(Date::now()->addDay());
        $this->expectException(InvalidEmailException::class);
        $this->_createInvite(['member_request_uuid' => $this->member_request_uuid]);
        $this->assertDatabaseMissing('invites', ['created_at' => Date::now()]);
    }

    /** @test */
    public function a_member_request_approval_creates_an_invite()
    {
        \MemberRequest::approveByUUID($this->member_request_uuid);
        $this->assertDatabaseHas('invites', ['created_at' => Date::now()]);
    }

    /** @test */
    public function an_invite_creation_sends_mail_to_email_address()
    {
        Mail::assertNothingSent();
        \MemberRequest::approveByUUID($this->member_request_uuid);
        $uuid = \Email::findByAddress($this->email_address)->invite->uuid;
        Mail::assertSent(InviteMail::class, function ($mail) use ($uuid) {

            return $mail->hasTo($this->email_address) &&
                $mail->url = env('app.url') . '/api/accept_invite/' . $uuid;
        });
    }
}