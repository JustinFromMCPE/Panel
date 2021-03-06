<?php

namespace Tests\Unit\Http\Middleware\API;

use Pterodactyl\Models\APIKey;
use Tests\Unit\Http\Middleware\MiddlewareTestCase;
use Pterodactyl\Http\Middleware\API\AuthenticateIPAccess;

class AuthenticateIPAccessTest extends MiddlewareTestCase
{
    /**
     * Setup tests.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Test middleware when there are no IP restrictions.
     */
    public function testWithNoIPRestrictions()
    {
        $model = factory(APIKey::class)->make(['allowed_ips' => []]);
        $this->setRequestAttribute('api_key', $model);

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test middleware works correctly when a valid IP accesses
     * and there is an IP restriction.
     */
    public function testWithValidIP()
    {
        $model = factory(APIKey::class)->make(['allowed_ips' => ['127.0.0.1']]);
        $this->setRequestAttribute('api_key', $model);

        $this->request->shouldReceive('ip')->withNoArgs()->once()->andReturn('127.0.0.1');

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that a CIDR range can be used.
     */
    public function testValidIPAganistCIDRRange()
    {
        $model = factory(APIKey::class)->make(['allowed_ips' => ['192.168.1.1/28']]);
        $this->setRequestAttribute('api_key', $model);

        $this->request->shouldReceive('ip')->withNoArgs()->once()->andReturn('192.168.1.15');

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Test that an exception is thrown when an invalid IP address
     * tries to connect and there is an IP restriction.
     *
     * @expectedException \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function testWithInvalidIP()
    {
        $model = factory(APIKey::class)->make(['allowed_ips' => ['127.0.0.1']]);
        $this->setRequestAttribute('api_key', $model);

        $this->request->shouldReceive('ip')->withNoArgs()->once()->andReturn('127.0.0.2');

        $this->getMiddleware()->handle($this->request, $this->getClosureAssertions());
    }

    /**
     * Return an instance of the middleware to be used when testing.
     *
     * @return \Pterodactyl\Http\Middleware\API\AuthenticateIPAccess
     */
    private function getMiddleware(): AuthenticateIPAccess
    {
        return new AuthenticateIPAccess();
    }
}
