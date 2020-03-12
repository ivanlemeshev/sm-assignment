<?php

use App\Service\Supermetrics\Credentials;
use Codeception\Test\Unit;

class SupermetricsCredentialsTest extends Unit
{
    protected Credentials $credentials;

    protected function _before()
    {
        $this->credentials = new Credentials('client_id', 'email@address.com', 'John Doe');
    }

    public function testGetClientId()
    {
        $this->assertEquals('client_id', $this->credentials->getClientId());
    }

    public function testGetEmail()
    {
        $this->assertEquals('email@address.com', $this->credentials->getEmail());
    }

    public function testGetName()
    {
        $this->assertEquals('John Doe', $this->credentials->getName());
    }
}
