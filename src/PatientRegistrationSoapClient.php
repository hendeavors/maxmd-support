<?php

namespace Endeavors\MaxMD\Support;

final class PatientRegistrationSoapClient extends SoapClient
{
    private static $instance = null;

    final private static function instance()
    {
        return static::$instance;
    }

    public static function getInstance()
    {
        if(null == static::instance()) {
            static::$instance = new PatientRegistrationSoapClient("https://api.directmdemail.com/AutoProofingSOAP/services/PatientIDProofingService?wsdl", array('trace' => 1));
        }

        return static::instance();
    }
}
