<?php

namespace Tests\Unit\Http\Controllers\Auth\Registration\Account;

use App\Invite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Date;
use PHPUnit\Framework\TestCase;

class CreateAccountTest extends TestCase
{
    use RefreshDatabase;

    private $invite_id;

    public function setUp(): void
    {
        parent::setUp();
        Date::setTestNow(Date::create(2020, 4, 7, 10, 43)->toImmutable());
        $invite = Invite::create();
        $this->invite_id = $invite->id;
        \Invite::acceptById($this->invite_id);
    }

    /** @test */
    public function an_account_can_be_created()
    {
        dd($this->invite_id);

    }
}
