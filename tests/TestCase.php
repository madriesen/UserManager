<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Mail;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected string $email_address = 'test@testing.com';
    protected string $name = 'driesen';
    protected string $first_name = 'martijn';

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }
}
