<?php
namespace Endeavors\MaxMD\Support\Tests;

use Orchestra\Testbench\TestCase;
use Endeavors\MaxMD\Support\ProofingRestClient;

class ProofingTest extends TestCase
{
    private static $sessionId;

    public function setUp()
    {
        parent::setUp();
        $dotEnv = new \Dotenv\Dotenv(dirname(__DIR__));
        $dotEnv->load();
    }

    public function test_login()
    {
        $client = ProofingRestClient::getInstance();
        $payload = ['username' => env('MAXMD_APIUSERNAME'), 'password' => env('MAXMD_APIPASSWORD')];
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
