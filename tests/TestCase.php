<?php
namespace Endeavors\MaxMD\Support\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Endeavors\MaxMD\Support\Domains;

class TestCase extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
        Domains::setDevelopmentMode(true);
    }
}
