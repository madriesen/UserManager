<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected string $email_address = 'test@testing.com';
    protected string $name = 'driesen';
    protected string $first_name = 'martijn';
}
