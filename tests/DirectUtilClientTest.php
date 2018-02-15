<?php

namespace Endeavors\MaxMD\Support\Tests;

use Endeavors\MaxMD\Support\Client;
use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Api\Auth\Session;

class DirectUtilClientTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testValidate()
    {
        MaxMD::Login(env("MAXMD_APIUSERNAME"),env("MAXMD_APIPASSWORD"));

        $client = Client::DirectUtil();

        $response = $client->ValidateRecipients([
            "sessionId" => Session::getId(),
            "ownerDirectAddress" => "stevejones1231224@healthendeavors.direct.eval.md",
            "recipients" => [
                "freddie@healthendeavors.direct.eval.md",
                "stevejones1231224@healthendeavors.direct.eval.md",
                "bad"
            ]
        ]);
    }
}