<?php
namespace Endeavors\MaxMD\Support\Tests;

use Endeavors\MaxMD\Support\Client;
use Endeavors\MaxMD\Support\ProofingRestClient;

class ProofingTest extends TestCase
{
    private static $sessionId;

    public function testLogin()
    {
        $client = Client::ProofingRest();
        $payload = ['username' => getenv('MAXMD_APIUSERNAME'), 'password' => getenv('MAXMD_APIPASSWORD')];
        $headers = ["Accept: application/json", "Content-Type: application/json"];
        $response = $client->Get('personal/logIn', $payload, $headers);
        $asArray = json_decode($response, true);

        $this->assertArrayHasKey('sessionId', $asArray);
        $this->assertArrayHasKey('success', $asArray);
        $this->assertArrayHasKey('code', $asArray);
        $this->assertArrayHasKey('message', $asArray);

        static::$sessionId = $asArray['sessionId'];
    }
}
