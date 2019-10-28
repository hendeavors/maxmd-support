<?php

namespace Endeavors\MaxMD\Support\Tests;

use Endeavors\MaxMD\Support\Client;
use Endeavors\MaxMD\Api\Auth\MaxMD;
use Endeavors\MaxMD\Api\Auth\Session;

class PatientRegistrationClientTest extends TestCase
{
    public function setUp()
    {
        MaxMD::Login(getenv("MAXMD_APIUSERNAME"),getenv("MAXMD_APIPASSWORD"));

        parent::setUp();
    }

    public function testResponseFromGetMessagesCall()
    {
        $registration = [
            'sessionId' => Session::getId(),
            'DirectDomain' => "healthendeavors.direct.eval.md",
            'DirectUsername' => "freddie"
        ];

        $response = Client::PatientRegistration()->GetPatientAddressByUsername($registration);

        $this->assertTrue(is_object($response));
    }

    public function testResponseFromProvisioningCall()
    {
        $registration = [
            'sessionId' => Session::getId(),
            'DirectDomain' => "healthendeavors.direct.eval.md",
            "patient" => ['idpId' => 4],
            'DirectUsername' => "freddie",
            'DirectPassword' => "smith"
        ];

        $response = Client::PatientRegistration()->ProvisionIDProofedPatient($registration);

        $this->assertTrue(is_object($response));
    }
}
