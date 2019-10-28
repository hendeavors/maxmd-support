<?php
namespace Endeavors\MaxMD\Support\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Endeavors\MaxMD\Support\Domains;

class DomainsTest extends BaseTestCase
{
    public function testLiveEndpoints()
    {
        Domains::setDevelopmentMode(false);

        $this->assertEquals('https://directapi.max.md:8445', Domains::rest());
        $this->assertEquals('https://api.directmdemail.com', Domains::soap());
        $this->assertEquals(Domains::rest(), Domains::providerDirectory());
    }

    public function testDevEndpoints()
    {
        Domains::setDevelopmentMode(true);

        $this->assertEquals('https://evalapi.max.md:8445', Domains::rest());
        $this->assertEquals('https://evalapi.max.md:8445', Domains::soap());
        $this->assertEquals('https://evalapi.max.md:8446', Domains::providerDirectory());
    }
}
