<?php

namespace Endeavors\MaxMD\Support;

// todo include a proofingsoap client
final class Client
{
    final public static function DirectMessage()
    {
        return DirectMessageSoapClient::getInstance();
    }

    final public static function ProofingRest()
    {
        return ProofingRestClient::getInstance();
    }

    final public static function PatientRegistration()
    {
        return PatientRegistrationSoapClient::getInstance();
    }

    final public static function DirectUtil()
    {
        return DirectUtilSoapClient::getInstance();
    }

    final public static function ProviderDirectoryRest()
    {
        return ProviderDirectoryRestClient::getInstance();
    }
}
